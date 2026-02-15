# Milestone 22: Mobile App Foundation — Customer App

## Smart Dairy Digital Smart Portal + ERP — Phase 3: E-Commerce, Mobile & Analytics

| Field            | Detail                                                        |
|------------------|---------------------------------------------------------------|
| **Milestone**    | 22 of 30 (2 of 10 in Phase 3)                                |
| **Title**        | Mobile App Foundation — Customer App                          |
| **Phase**        | Phase 3 — E-Commerce, Mobile & Analytics (Part A: E-Commerce & Mobile) |
| **Days**         | Days 211–220 (of 300)                                        |
| **Version**      | 1.0                                                          |
| **Status**       | Draft                                                        |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03                                                   |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Build the Smart Dairy customer mobile application using Flutter 3.16+ with Clean Architecture principles and BLoC state management. Implement core features including JWT authentication with social login, product browsing with offline caching using Hive, shopping cart with real-time sync, checkout flow with address management, order history with tracking, and Firebase Cloud Messaging for push notifications. Prepare the app for beta release on both Android and iOS platforms.

### 1.2 Objectives

1. Set up Flutter 3.16+ project with Clean Architecture folder structure
2. Implement BLoC state management pattern with flutter_bloc 8.1+
3. Create authentication module with JWT token management and refresh
4. Add social login support (Google, Facebook, Phone OTP)
5. Build product browsing screens with offline-first caching using Hive
6. Implement shopping cart with real-time synchronization
7. Develop checkout flow with multiple payment options
8. Create order history and real-time order tracking
9. Integrate Firebase Cloud Messaging for push notifications
10. Prepare app for Google Play Store and Apple App Store submission

### 1.3 Key Deliverables

| # | Deliverable | Owner | Day |
|---|-------------|-------|-----|
| D22.1 | Flutter project setup with Clean Architecture | Dev 3 | 211 |
| D22.2 | Core infrastructure (DI, routing, theming) | Dev 3 | 211 |
| D22.3 | Authentication module with BLoC | Dev 3 | 212 |
| D22.4 | Social login (Google, Facebook) | Dev 3 | 212-213 |
| D22.5 | Phone OTP authentication | Dev 3 | 213 |
| D22.6 | Product browsing with categories | Dev 3 | 214 |
| D22.7 | Product detail screen | Dev 3 | 214-215 |
| D22.8 | Offline caching with Hive | Dev 3 | 215 |
| D22.9 | Shopping cart implementation | Dev 3 | 216 |
| D22.10 | Checkout flow | Dev 3 | 217 |
| D22.11 | Payment integration (mobile) | Dev 2 | 217-218 |
| D22.12 | Order history and tracking | Dev 3 | 218 |
| D22.13 | Push notifications (Firebase) | Dev 3 | 219 |
| D22.14 | App store preparation and beta release | All | 220 |

### 1.4 Success Criteria

- [ ] Flutter app running on both Android (minSdk 21) and iOS (iOS 12+)
- [ ] JWT authentication with automatic token refresh
- [ ] Offline product browsing with <100ms cache retrieval
- [ ] Shopping cart sync between app and web within 5 seconds
- [ ] Checkout supporting bKash, Nagad, and card payments
- [ ] Order tracking with real-time status updates
- [ ] Push notifications delivered with >95% reliability
- [ ] App size <50MB (Android APK), <70MB (iOS IPA)
- [ ] Cold start time <3 seconds on mid-range devices
- [ ] Beta release on both Play Store (Internal Testing) and TestFlight

### 1.5 Prerequisites

- **From Phase 2:**
  - API Gateway operational (MS-20)
  - Payment gateway integration (MS-18)
  - Customer authentication system

- **From Phase 3:**
  - B2C E-Commerce APIs (MS-21)
  - Search API with Elasticsearch
  - Wishlist and Reviews APIs

- **External:**
  - Google Play Developer Account (BDT 2,000 one-time)
  - Apple Developer Account (BDT 8,500/year)
  - Firebase project created

---

## 2. Requirement Traceability Matrix

### 2.1 BRD Requirements Mapping

| BRD Req ID | Requirement Description | SRS Trace | Day | Owner |
|-----------|------------------------|-----------|-----|-------|
| BRD-MOB-001 | Cross-platform mobile app | SRS-MOB-001 | 211 | Dev 3 |
| BRD-MOB-002 | User authentication | SRS-MOB-002 | 212-213 | Dev 3 |
| BRD-MOB-003 | Product catalog browsing | SRS-MOB-003 | 214-215 | Dev 3 |
| BRD-MOB-004 | Offline functionality | SRS-MOB-004 | 215 | Dev 3 |
| BRD-MOB-005 | Shopping cart | SRS-MOB-005 | 216 | Dev 3 |
| BRD-MOB-006 | Checkout and payment | SRS-MOB-006 | 217-218 | Dev 3/Dev 2 |
| BRD-MOB-007 | Order management | SRS-MOB-007 | 218 | Dev 3 |
| BRD-MOB-008 | Push notifications | SRS-MOB-008 | 219 | Dev 3 |
| BRD-MOB-009 | App performance | SRS-PERF-004 | 220 | Dev 3 |
| BRD-MOB-010 | App store compliance | SRS-MOB-010 | 220 | All |

### 2.2 Technology Stack

| Component | Technology | Version | Purpose |
|-----------|------------|---------|---------|
| Framework | Flutter | 3.16+ | Cross-platform UI |
| Language | Dart | 3.2+ | Programming language |
| State Management | flutter_bloc | 8.1+ | BLoC pattern |
| HTTP Client | dio | 5.3+ | API communication |
| Local Storage | hive | 2.2+ | Offline caching |
| Routing | go_router | 12.0+ | Declarative routing |
| DI | get_it + injectable | Latest | Dependency injection |
| Push Notifications | firebase_messaging | 14.6+ | FCM integration |
| Auth | firebase_auth | 4.10+ | Social login |
| Image Loading | cached_network_image | 3.3+ | Image caching |

---

## 3. Day-by-Day Breakdown

---

### Day 211 — Project Setup and Core Infrastructure

**Objective:** Initialize Flutter project with Clean Architecture structure, configure dependency injection, routing, and theming.

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 211.3.1: Flutter Project Initialization (2h)**

```bash
# Create Flutter project
flutter create --org com.smartdairy --project-name smart_dairy_app smart_dairy_app

# Navigate to project
cd smart_dairy_app

# Add dependencies to pubspec.yaml
```

```yaml
# pubspec.yaml
name: smart_dairy_app
description: Smart Dairy Customer Mobile Application
publish_to: 'none'
version: 1.0.0+1

environment:
  sdk: '>=3.2.0 <4.0.0'

dependencies:
  flutter:
    sdk: flutter
  flutter_localizations:
    sdk: flutter

  # State Management
  flutter_bloc: ^8.1.3
  equatable: ^2.0.5

  # Networking
  dio: ^5.3.4
  pretty_dio_logger: ^1.3.1

  # Local Storage
  hive: ^2.2.3
  hive_flutter: ^1.1.0

  # Routing
  go_router: ^12.1.1

  # Dependency Injection
  get_it: ^7.6.4
  injectable: ^2.3.2

  # Firebase
  firebase_core: ^2.24.2
  firebase_messaging: ^14.7.10
  firebase_auth: ^4.16.0

  # Social Login
  google_sign_in: ^6.2.1
  flutter_facebook_auth: ^6.0.4

  # UI Components
  cached_network_image: ^3.3.0
  flutter_svg: ^2.0.9
  shimmer: ^3.0.0
  lottie: ^2.7.0

  # Utilities
  intl: ^0.18.1
  url_launcher: ^6.2.2
  share_plus: ^7.2.1
  package_info_plus: ^5.0.1
  connectivity_plus: ^5.0.2

  # Payment
  flutter_inappwebview: ^6.0.0

  # Image Picker
  image_picker: ^1.0.4

  # Forms
  formz: ^0.6.1

  # JSON Serialization
  json_annotation: ^4.8.1
  freezed_annotation: ^2.4.1

dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.1
  build_runner: ^2.4.7
  hive_generator: ^2.0.1
  json_serializable: ^6.7.1
  freezed: ^2.4.6
  injectable_generator: ^2.4.1
  bloc_test: ^9.1.5
  mocktail: ^1.0.1

flutter:
  uses-material-design: true

  assets:
    - assets/images/
    - assets/icons/
    - assets/animations/

  fonts:
    - family: Poppins
      fonts:
        - asset: assets/fonts/Poppins-Regular.ttf
        - asset: assets/fonts/Poppins-Medium.ttf
          weight: 500
        - asset: assets/fonts/Poppins-SemiBold.ttf
          weight: 600
        - asset: assets/fonts/Poppins-Bold.ttf
          weight: 700
```

**Task 211.3.2: Clean Architecture Folder Structure (2h)**

```
lib/
├── main.dart
├── app.dart
├── injection.dart
├── injection.config.dart
│
├── core/
│   ├── constants/
│   │   ├── api_constants.dart
│   │   ├── app_constants.dart
│   │   └── storage_constants.dart
│   │
│   ├── error/
│   │   ├── exceptions.dart
│   │   └── failures.dart
│   │
│   ├── network/
│   │   ├── api_client.dart
│   │   ├── api_interceptors.dart
│   │   └── network_info.dart
│   │
│   ├── theme/
│   │   ├── app_theme.dart
│   │   ├── app_colors.dart
│   │   └── app_text_styles.dart
│   │
│   ├── router/
│   │   ├── app_router.dart
│   │   └── route_names.dart
│   │
│   ├── utils/
│   │   ├── validators.dart
│   │   ├── formatters.dart
│   │   └── extensions.dart
│   │
│   └── widgets/
│       ├── app_button.dart
│       ├── app_text_field.dart
│       ├── loading_widget.dart
│       └── error_widget.dart
│
├── features/
│   ├── auth/
│   │   ├── data/
│   │   │   ├── datasources/
│   │   │   │   ├── auth_local_datasource.dart
│   │   │   │   └── auth_remote_datasource.dart
│   │   │   ├── models/
│   │   │   │   ├── user_model.dart
│   │   │   │   └── auth_response_model.dart
│   │   │   └── repositories/
│   │   │       └── auth_repository_impl.dart
│   │   │
│   │   ├── domain/
│   │   │   ├── entities/
│   │   │   │   └── user.dart
│   │   │   ├── repositories/
│   │   │   │   └── auth_repository.dart
│   │   │   └── usecases/
│   │   │       ├── login_usecase.dart
│   │   │       ├── register_usecase.dart
│   │   │       └── logout_usecase.dart
│   │   │
│   │   └── presentation/
│   │       ├── bloc/
│   │       │   ├── auth_bloc.dart
│   │       │   ├── auth_event.dart
│   │       │   └── auth_state.dart
│   │       ├── pages/
│   │       │   ├── login_page.dart
│   │       │   ├── register_page.dart
│   │       │   └── otp_verification_page.dart
│   │       └── widgets/
│   │           ├── login_form.dart
│   │           └── social_login_buttons.dart
│   │
│   ├── products/
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   │
│   ├── cart/
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   │
│   ├── checkout/
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   │
│   ├── orders/
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   │
│   └── profile/
│       ├── data/
│       ├── domain/
│       └── presentation/
│
└── l10n/
    ├── app_en.arb
    └── app_bn.arb
```

**Task 211.3.3: Dependency Injection Setup (2h)**

```dart
// lib/injection.dart
import 'package:get_it/get_it.dart';
import 'package:injectable/injectable.dart';
import 'package:smart_dairy_app/injection.config.dart';

final getIt = GetIt.instance;

@InjectableInit(
  initializerName: 'init',
  preferRelativeImports: true,
  asExtension: true,
)
Future<void> configureDependencies() async => getIt.init();
```

```dart
// lib/core/network/api_client.dart
import 'package:dio/dio.dart';
import 'package:injectable/injectable.dart';
import 'package:smart_dairy_app/core/constants/api_constants.dart';
import 'package:smart_dairy_app/core/network/api_interceptors.dart';

@module
abstract class NetworkModule {
  @lazySingleton
  Dio get dio {
    final dio = Dio(
      BaseOptions(
        baseUrl: ApiConstants.baseUrl,
        connectTimeout: const Duration(seconds: 30),
        receiveTimeout: const Duration(seconds: 30),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ),
    );

    dio.interceptors.addAll([
      AuthInterceptor(),
      LoggingInterceptor(),
      ErrorInterceptor(),
    ]);

    return dio;
  }
}

@injectable
class ApiClient {
  final Dio _dio;

  ApiClient(this._dio);

  Future<Response<T>> get<T>(
    String path, {
    Map<String, dynamic>? queryParameters,
    Options? options,
  }) async {
    return _dio.get<T>(
      path,
      queryParameters: queryParameters,
      options: options,
    );
  }

  Future<Response<T>> post<T>(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    Options? options,
  }) async {
    return _dio.post<T>(
      path,
      data: data,
      queryParameters: queryParameters,
      options: options,
    );
  }

  Future<Response<T>> put<T>(
    String path, {
    dynamic data,
    Options? options,
  }) async {
    return _dio.put<T>(
      path,
      data: data,
      options: options,
    );
  }

  Future<Response<T>> delete<T>(
    String path, {
    dynamic data,
    Options? options,
  }) async {
    return _dio.delete<T>(
      path,
      data: data,
      options: options,
    );
  }
}
```

**Task 211.3.4: App Theme Configuration (2h)**

```dart
// lib/core/theme/app_colors.dart
import 'package:flutter/material.dart';

class AppColors {
  // Primary Colors
  static const Color primary = Color(0xFF2563EB);
  static const Color primaryLight = Color(0xFF60A5FA);
  static const Color primaryDark = Color(0xFF1D4ED8);

  // Secondary Colors
  static const Color secondary = Color(0xFF10B981);
  static const Color secondaryLight = Color(0xFF34D399);
  static const Color secondaryDark = Color(0xFF059669);

  // Neutral Colors
  static const Color background = Color(0xFFF8FAFC);
  static const Color surface = Color(0xFFFFFFFF);
  static const Color textPrimary = Color(0xFF1E293B);
  static const Color textSecondary = Color(0xFF64748B);
  static const Color textHint = Color(0xFF94A3B8);
  static const Color border = Color(0xFFE2E8F0);
  static const Color divider = Color(0xFFF1F5F9);

  // Semantic Colors
  static const Color success = Color(0xFF22C55E);
  static const Color warning = Color(0xFFF59E0B);
  static const Color error = Color(0xFFEF4444);
  static const Color info = Color(0xFF3B82F6);

  // Brand Colors
  static const Color milkWhite = Color(0xFFFFFBF5);
  static const Color creamYellow = Color(0xFFFFF8E7);
  static const Color grassGreen = Color(0xFF4ADE80);
}
```

```dart
// lib/core/theme/app_theme.dart
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:smart_dairy_app/core/theme/app_colors.dart';
import 'package:smart_dairy_app/core/theme/app_text_styles.dart';

class AppTheme {
  static ThemeData get lightTheme {
    return ThemeData(
      useMaterial3: true,
      brightness: Brightness.light,
      primaryColor: AppColors.primary,
      scaffoldBackgroundColor: AppColors.background,
      colorScheme: const ColorScheme.light(
        primary: AppColors.primary,
        primaryContainer: AppColors.primaryLight,
        secondary: AppColors.secondary,
        secondaryContainer: AppColors.secondaryLight,
        surface: AppColors.surface,
        background: AppColors.background,
        error: AppColors.error,
        onPrimary: Colors.white,
        onSecondary: Colors.white,
        onSurface: AppColors.textPrimary,
        onBackground: AppColors.textPrimary,
        onError: Colors.white,
      ),
      fontFamily: 'Poppins',
      textTheme: AppTextStyles.textTheme,
      appBarTheme: const AppBarTheme(
        backgroundColor: AppColors.surface,
        foregroundColor: AppColors.textPrimary,
        elevation: 0,
        centerTitle: true,
        systemOverlayStyle: SystemUiOverlayStyle.dark,
      ),
      cardTheme: CardTheme(
        color: AppColors.surface,
        elevation: 2,
        shadowColor: Colors.black.withOpacity(0.05),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
        ),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.primary,
          foregroundColor: Colors.white,
          elevation: 0,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
          textStyle: AppTextStyles.button,
        ),
      ),
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          foregroundColor: AppColors.primary,
          side: const BorderSide(color: AppColors.primary),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
          textStyle: AppTextStyles.button,
        ),
      ),
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(
          foregroundColor: AppColors.primary,
          textStyle: AppTextStyles.button,
        ),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: AppColors.surface,
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppColors.border),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppColors.border),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppColors.primary, width: 2),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppColors.error),
        ),
        hintStyle: AppTextStyles.bodyMedium.copyWith(color: AppColors.textHint),
      ),
      bottomNavigationBarTheme: const BottomNavigationBarThemeData(
        backgroundColor: AppColors.surface,
        selectedItemColor: AppColors.primary,
        unselectedItemColor: AppColors.textSecondary,
        type: BottomNavigationBarType.fixed,
        elevation: 8,
      ),
      dividerTheme: const DividerThemeData(
        color: AppColors.divider,
        thickness: 1,
      ),
    );
  }
}
```

```dart
// lib/core/router/app_router.dart
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:smart_dairy_app/core/router/route_names.dart';
import 'package:smart_dairy_app/features/auth/presentation/pages/login_page.dart';
import 'package:smart_dairy_app/features/auth/presentation/pages/register_page.dart';
import 'package:smart_dairy_app/features/products/presentation/pages/home_page.dart';
import 'package:smart_dairy_app/features/products/presentation/pages/product_detail_page.dart';
import 'package:smart_dairy_app/features/cart/presentation/pages/cart_page.dart';
import 'package:smart_dairy_app/features/checkout/presentation/pages/checkout_page.dart';
import 'package:smart_dairy_app/features/orders/presentation/pages/orders_page.dart';
import 'package:smart_dairy_app/features/profile/presentation/pages/profile_page.dart';
import 'package:smart_dairy_app/features/main/presentation/pages/main_page.dart';
import 'package:smart_dairy_app/features/splash/presentation/pages/splash_page.dart';

class AppRouter {
  static final _rootNavigatorKey = GlobalKey<NavigatorState>();
  static final _shellNavigatorKey = GlobalKey<NavigatorState>();

  static final router = GoRouter(
    navigatorKey: _rootNavigatorKey,
    initialLocation: RouteNames.splash,
    debugLogDiagnostics: true,
    routes: [
      GoRoute(
        path: RouteNames.splash,
        builder: (context, state) => const SplashPage(),
      ),
      GoRoute(
        path: RouteNames.login,
        builder: (context, state) => const LoginPage(),
      ),
      GoRoute(
        path: RouteNames.register,
        builder: (context, state) => const RegisterPage(),
      ),
      ShellRoute(
        navigatorKey: _shellNavigatorKey,
        builder: (context, state, child) => MainPage(child: child),
        routes: [
          GoRoute(
            path: RouteNames.home,
            pageBuilder: (context, state) => const NoTransitionPage(
              child: HomePage(),
            ),
          ),
          GoRoute(
            path: RouteNames.categories,
            pageBuilder: (context, state) => const NoTransitionPage(
              child: CategoriesPage(),
            ),
          ),
          GoRoute(
            path: RouteNames.cart,
            pageBuilder: (context, state) => const NoTransitionPage(
              child: CartPage(),
            ),
          ),
          GoRoute(
            path: RouteNames.orders,
            pageBuilder: (context, state) => const NoTransitionPage(
              child: OrdersPage(),
            ),
          ),
          GoRoute(
            path: RouteNames.profile,
            pageBuilder: (context, state) => const NoTransitionPage(
              child: ProfilePage(),
            ),
          ),
        ],
      ),
      GoRoute(
        path: '${RouteNames.productDetail}/:id',
        builder: (context, state) {
          final productId = int.parse(state.pathParameters['id']!);
          return ProductDetailPage(productId: productId);
        },
      ),
      GoRoute(
        path: RouteNames.checkout,
        builder: (context, state) => const CheckoutPage(),
      ),
    ],
  );
}
```

#### Dev 1 — Backend Lead (8h)

**Task 211.1.1: Mobile API Endpoints Preparation (4h)**

```python
# api/routers/mobile.py
from fastapi import APIRouter, Depends, HTTPException, Header
from typing import Optional
from pydantic import BaseModel
import jwt

router = APIRouter(prefix="/api/v1/mobile", tags=["mobile"])

class AppConfigResponse(BaseModel):
    min_version: str
    current_version: str
    force_update: bool
    maintenance_mode: bool
    maintenance_message: Optional[str]
    features: dict

@router.get("/config", response_model=AppConfigResponse)
async def get_app_config(
    platform: str = Header(..., alias="X-Platform"),
    app_version: str = Header(..., alias="X-App-Version")
):
    """
    Get mobile app configuration

    Returns app version requirements, maintenance status, and feature flags.
    """
    config = {
        "min_version": "1.0.0",
        "current_version": "1.0.0",
        "force_update": False,
        "maintenance_mode": False,
        "maintenance_message": None,
        "features": {
            "social_login": True,
            "phone_otp": True,
            "push_notifications": True,
            "offline_mode": True,
            "dark_mode": False,
            "biometric_auth": True,
            "in_app_payment": True,
        }
    }

    # Version comparison logic
    if _compare_versions(app_version, config["min_version"]) < 0:
        config["force_update"] = True

    return AppConfigResponse(**config)

def _compare_versions(v1: str, v2: str) -> int:
    """Compare semantic versions: -1 if v1 < v2, 0 if equal, 1 if v1 > v2"""
    parts1 = [int(x) for x in v1.split('.')]
    parts2 = [int(x) for x in v2.split('.')]

    for i in range(max(len(parts1), len(parts2))):
        p1 = parts1[i] if i < len(parts1) else 0
        p2 = parts2[i] if i < len(parts2) else 0
        if p1 < p2:
            return -1
        if p1 > p2:
            return 1
    return 0
```

**Task 211.1.2: Mobile Authentication API (4h)**

```python
# api/routers/mobile_auth.py
from fastapi import APIRouter, Depends, HTTPException, status
from pydantic import BaseModel, EmailStr, Field
from typing import Optional
import secrets
from datetime import datetime, timedelta

router = APIRouter(prefix="/api/v1/mobile/auth", tags=["mobile-auth"])

class LoginRequest(BaseModel):
    email: Optional[EmailStr] = None
    phone: Optional[str] = None
    password: Optional[str] = None
    otp: Optional[str] = None
    login_type: str = Field(..., pattern="^(email|phone|google|facebook)$")
    social_token: Optional[str] = None

class LoginResponse(BaseModel):
    success: bool
    access_token: Optional[str]
    refresh_token: Optional[str]
    token_type: str = "Bearer"
    expires_in: int
    user: Optional[dict]

class OTPRequest(BaseModel):
    phone: str = Field(..., pattern="^\\+880[0-9]{10}$")

class OTPResponse(BaseModel):
    success: bool
    message: str
    expires_in: int = 300  # 5 minutes

class RefreshTokenRequest(BaseModel):
    refresh_token: str

@router.post("/login", response_model=LoginResponse)
async def mobile_login(request: LoginRequest):
    """
    Mobile app login endpoint

    Supports:
    - Email/password login
    - Phone OTP login
    - Google social login
    - Facebook social login
    """
    try:
        if request.login_type == "email":
            if not request.email or not request.password:
                raise HTTPException(
                    status_code=400,
                    detail="Email and password required for email login"
                )
            user = await _authenticate_email(request.email, request.password)

        elif request.login_type == "phone":
            if not request.phone or not request.otp:
                raise HTTPException(
                    status_code=400,
                    detail="Phone and OTP required for phone login"
                )
            user = await _authenticate_phone_otp(request.phone, request.otp)

        elif request.login_type == "google":
            if not request.social_token:
                raise HTTPException(
                    status_code=400,
                    detail="Google token required"
                )
            user = await _authenticate_google(request.social_token)

        elif request.login_type == "facebook":
            if not request.social_token:
                raise HTTPException(
                    status_code=400,
                    detail="Facebook token required"
                )
            user = await _authenticate_facebook(request.social_token)

        else:
            raise HTTPException(status_code=400, detail="Invalid login type")

        # Generate tokens
        access_token = _generate_access_token(user)
        refresh_token = _generate_refresh_token(user)

        return LoginResponse(
            success=True,
            access_token=access_token,
            refresh_token=refresh_token,
            expires_in=3600,  # 1 hour
            user={
                "id": user.id,
                "name": user.name,
                "email": user.email,
                "phone": user.phone,
                "avatar_url": user.avatar_url,
            }
        )

    except Exception as e:
        raise HTTPException(status_code=401, detail=str(e))

@router.post("/send-otp", response_model=OTPResponse)
async def send_otp(request: OTPRequest):
    """Send OTP to phone number for verification"""
    try:
        # Generate 6-digit OTP
        otp = ''.join([str(secrets.randbelow(10)) for _ in range(6)])

        # Store OTP with expiration (Redis or DB)
        await _store_otp(request.phone, otp, expires_in=300)

        # Send OTP via SMS gateway
        await _send_sms(request.phone, f"Your Smart Dairy verification code is: {otp}")

        return OTPResponse(
            success=True,
            message="OTP sent successfully",
            expires_in=300
        )
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.post("/refresh", response_model=LoginResponse)
async def refresh_token(request: RefreshTokenRequest):
    """Refresh access token using refresh token"""
    try:
        payload = _verify_refresh_token(request.refresh_token)
        user_id = payload.get("user_id")

        user = await _get_user_by_id(user_id)
        if not user:
            raise HTTPException(status_code=401, detail="User not found")

        access_token = _generate_access_token(user)

        return LoginResponse(
            success=True,
            access_token=access_token,
            refresh_token=request.refresh_token,
            expires_in=3600,
            user=None
        )
    except Exception as e:
        raise HTTPException(status_code=401, detail="Invalid refresh token")

@router.post("/logout")
async def logout(refresh_token: str = Header(..., alias="X-Refresh-Token")):
    """Logout and invalidate refresh token"""
    try:
        await _invalidate_refresh_token(refresh_token)
        return {"success": True, "message": "Logged out successfully"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Task 211.2.1: Firebase Project Setup (4h)**

```javascript
// Firebase configuration for Smart Dairy App
// firebase.json
{
  "functions": {
    "source": "functions",
    "runtime": "nodejs18"
  },
  "hosting": {
    "public": "public",
    "ignore": ["firebase.json", "**/.*", "**/node_modules/**"]
  },
  "firestore": {
    "rules": "firestore.rules",
    "indexes": "firestore.indexes.json"
  }
}
```

```yaml
# android/app/google-services.json (template)
{
  "project_info": {
    "project_number": "YOUR_PROJECT_NUMBER",
    "project_id": "smart-dairy-app",
    "storage_bucket": "smart-dairy-app.appspot.com"
  },
  "client": [
    {
      "client_info": {
        "mobilesdk_app_id": "YOUR_ANDROID_APP_ID",
        "android_client_info": {
          "package_name": "com.smartdairy.app"
        }
      },
      "oauth_client": [],
      "api_key": [
        {
          "current_key": "YOUR_API_KEY"
        }
      ],
      "services": {
        "appinvite_service": {
          "other_platform_oauth_client": []
        }
      }
    }
  ],
  "configuration_version": "1"
}
```

**Task 211.2.2: CI/CD Pipeline for Mobile (4h)**

```yaml
# .github/workflows/mobile-app.yml
name: Smart Dairy Mobile App

on:
  push:
    branches: [main, develop]
    paths:
      - 'mobile/**'
  pull_request:
    branches: [main, develop]
    paths:
      - 'mobile/**'

env:
  FLUTTER_VERSION: '3.16.0'
  JAVA_VERSION: '17'

jobs:
  analyze:
    name: Analyze & Test
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Setup Java
        uses: actions/setup-java@v4
        with:
          distribution: 'temurin'
          java-version: ${{ env.JAVA_VERSION }}

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: ${{ env.FLUTTER_VERSION }}
          channel: 'stable'
          cache: true

      - name: Get dependencies
        run: flutter pub get
        working-directory: mobile

      - name: Run code generation
        run: flutter pub run build_runner build --delete-conflicting-outputs
        working-directory: mobile

      - name: Analyze code
        run: flutter analyze
        working-directory: mobile

      - name: Run tests
        run: flutter test --coverage
        working-directory: mobile

      - name: Upload coverage
        uses: codecov/codecov-action@v3
        with:
          file: mobile/coverage/lcov.info
          flags: mobile

  build-android:
    name: Build Android
    runs-on: ubuntu-latest
    needs: analyze
    if: github.ref == 'refs/heads/main' || github.ref == 'refs/heads/develop'
    steps:
      - uses: actions/checkout@v4

      - name: Setup Java
        uses: actions/setup-java@v4
        with:
          distribution: 'temurin'
          java-version: ${{ env.JAVA_VERSION }}

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: ${{ env.FLUTTER_VERSION }}
          channel: 'stable'
          cache: true

      - name: Get dependencies
        run: flutter pub get
        working-directory: mobile

      - name: Build APK
        run: flutter build apk --release
        working-directory: mobile

      - name: Build App Bundle
        run: flutter build appbundle --release
        working-directory: mobile

      - name: Upload APK
        uses: actions/upload-artifact@v3
        with:
          name: android-apk
          path: mobile/build/app/outputs/flutter-apk/app-release.apk

      - name: Upload AAB
        uses: actions/upload-artifact@v3
        with:
          name: android-aab
          path: mobile/build/app/outputs/bundle/release/app-release.aab

  build-ios:
    name: Build iOS
    runs-on: macos-latest
    needs: analyze
    if: github.ref == 'refs/heads/main' || github.ref == 'refs/heads/develop'
    steps:
      - uses: actions/checkout@v4

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: ${{ env.FLUTTER_VERSION }}
          channel: 'stable'
          cache: true

      - name: Get dependencies
        run: flutter pub get
        working-directory: mobile

      - name: Build iOS (no signing)
        run: flutter build ios --release --no-codesign
        working-directory: mobile

      - name: Upload iOS build
        uses: actions/upload-artifact@v3
        with:
          name: ios-build
          path: mobile/build/ios/iphoneos/Runner.app
```

**End-of-Day 211 Deliverables:**
- [ ] Flutter project initialized with pubspec.yaml
- [ ] Clean Architecture folder structure created
- [ ] Dependency injection with get_it and injectable configured
- [ ] App theme with colors, typography, and component styles
- [ ] GoRouter navigation setup with shell routes
- [ ] Mobile API endpoints for config and auth prepared
- [ ] Firebase project configured for FCM
- [ ] CI/CD pipeline for mobile builds

---

### Day 212 — Authentication Module with BLoC

**Objective:** Implement complete authentication flow using BLoC pattern with email/password login, JWT token management, and secure storage.

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 212.3.1: Auth BLoC Implementation (4h)**

```dart
// lib/features/auth/presentation/bloc/auth_bloc.dart
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:injectable/injectable.dart';
import 'package:smart_dairy_app/features/auth/domain/entities/user.dart';
import 'package:smart_dairy_app/features/auth/domain/usecases/login_usecase.dart';
import 'package:smart_dairy_app/features/auth/domain/usecases/logout_usecase.dart';
import 'package:smart_dairy_app/features/auth/domain/usecases/check_auth_usecase.dart';

part 'auth_event.dart';
part 'auth_state.dart';

@injectable
class AuthBloc extends Bloc<AuthEvent, AuthState> {
  final LoginUseCase _loginUseCase;
  final LogoutUseCase _logoutUseCase;
  final CheckAuthUseCase _checkAuthUseCase;

  AuthBloc(
    this._loginUseCase,
    this._logoutUseCase,
    this._checkAuthUseCase,
  ) : super(AuthInitial()) {
    on<CheckAuthStatus>(_onCheckAuthStatus);
    on<LoginWithEmail>(_onLoginWithEmail);
    on<LoginWithPhone>(_onLoginWithPhone);
    on<LoginWithGoogle>(_onLoginWithGoogle);
    on<LoginWithFacebook>(_onLoginWithFacebook);
    on<Logout>(_onLogout);
  }

  Future<void> _onCheckAuthStatus(
    CheckAuthStatus event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());

    final result = await _checkAuthUseCase();

    result.fold(
      (failure) => emit(Unauthenticated()),
      (user) => emit(Authenticated(user: user)),
    );
  }

  Future<void> _onLoginWithEmail(
    LoginWithEmail event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());

    final result = await _loginUseCase(
      LoginParams(
        loginType: LoginType.email,
        email: event.email,
        password: event.password,
      ),
    );

    result.fold(
      (failure) => emit(AuthError(message: failure.message)),
      (user) => emit(Authenticated(user: user)),
    );
  }

  Future<void> _onLoginWithPhone(
    LoginWithPhone event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());

    final result = await _loginUseCase(
      LoginParams(
        loginType: LoginType.phone,
        phone: event.phone,
        otp: event.otp,
      ),
    );

    result.fold(
      (failure) => emit(AuthError(message: failure.message)),
      (user) => emit(Authenticated(user: user)),
    );
  }

  Future<void> _onLoginWithGoogle(
    LoginWithGoogle event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());

    final result = await _loginUseCase(
      LoginParams(
        loginType: LoginType.google,
        socialToken: event.idToken,
      ),
    );

    result.fold(
      (failure) => emit(AuthError(message: failure.message)),
      (user) => emit(Authenticated(user: user)),
    );
  }

  Future<void> _onLoginWithFacebook(
    LoginWithFacebook event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());

    final result = await _loginUseCase(
      LoginParams(
        loginType: LoginType.facebook,
        socialToken: event.accessToken,
      ),
    );

    result.fold(
      (failure) => emit(AuthError(message: failure.message)),
      (user) => emit(Authenticated(user: user)),
    );
  }

  Future<void> _onLogout(
    Logout event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());

    final result = await _logoutUseCase();

    result.fold(
      (failure) => emit(AuthError(message: failure.message)),
      (_) => emit(Unauthenticated()),
    );
  }
}
```

```dart
// lib/features/auth/presentation/bloc/auth_event.dart
part of 'auth_bloc.dart';

abstract class AuthEvent extends Equatable {
  const AuthEvent();

  @override
  List<Object?> get props => [];
}

class CheckAuthStatus extends AuthEvent {}

class LoginWithEmail extends AuthEvent {
  final String email;
  final String password;

  const LoginWithEmail({
    required this.email,
    required this.password,
  });

  @override
  List<Object> get props => [email, password];
}

class LoginWithPhone extends AuthEvent {
  final String phone;
  final String otp;

  const LoginWithPhone({
    required this.phone,
    required this.otp,
  });

  @override
  List<Object> get props => [phone, otp];
}

class LoginWithGoogle extends AuthEvent {
  final String idToken;

  const LoginWithGoogle({required this.idToken});

  @override
  List<Object> get props => [idToken];
}

class LoginWithFacebook extends AuthEvent {
  final String accessToken;

  const LoginWithFacebook({required this.accessToken});

  @override
  List<Object> get props => [accessToken];
}

class Logout extends AuthEvent {}
```

```dart
// lib/features/auth/presentation/bloc/auth_state.dart
part of 'auth_bloc.dart';

abstract class AuthState extends Equatable {
  const AuthState();

  @override
  List<Object?> get props => [];
}

class AuthInitial extends AuthState {}

class AuthLoading extends AuthState {}

class Authenticated extends AuthState {
  final User user;

  const Authenticated({required this.user});

  @override
  List<Object> get props => [user];
}

class Unauthenticated extends AuthState {}

class AuthError extends AuthState {
  final String message;

  const AuthError({required this.message});

  @override
  List<Object> get props => [message];
}
```

**Task 212.3.2: Login Page UI (4h)**

```dart
// lib/features/auth/presentation/pages/login_page.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import 'package:smart_dairy_app/core/router/route_names.dart';
import 'package:smart_dairy_app/core/theme/app_colors.dart';
import 'package:smart_dairy_app/core/widgets/app_button.dart';
import 'package:smart_dairy_app/core/widgets/app_text_field.dart';
import 'package:smart_dairy_app/features/auth/presentation/bloc/auth_bloc.dart';
import 'package:smart_dairy_app/features/auth/presentation/widgets/social_login_buttons.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _onLogin() {
    if (_formKey.currentState?.validate() ?? false) {
      context.read<AuthBloc>().add(
            LoginWithEmail(
              email: _emailController.text.trim(),
              password: _passwordController.text,
            ),
          );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocConsumer<AuthBloc, AuthState>(
        listener: (context, state) {
          if (state is Authenticated) {
            context.go(RouteNames.home);
          } else if (state is AuthError) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(state.message),
                backgroundColor: AppColors.error,
              ),
            );
          }
        },
        builder: (context, state) {
          return SafeArea(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(24),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    const SizedBox(height: 48),

                    // Logo
                    Center(
                      child: Image.asset(
                        'assets/images/logo.png',
                        height: 80,
                      ),
                    ),
                    const SizedBox(height: 24),

                    // Title
                    Text(
                      'Welcome Back',
                      style: Theme.of(context).textTheme.headlineMedium?.copyWith(
                            fontWeight: FontWeight.bold,
                          ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'Sign in to continue shopping fresh dairy products',
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                            color: AppColors.textSecondary,
                          ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 48),

                    // Email Field
                    AppTextField(
                      controller: _emailController,
                      label: 'Email',
                      hintText: 'Enter your email',
                      keyboardType: TextInputType.emailAddress,
                      prefixIcon: Icons.email_outlined,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Please enter your email';
                        }
                        if (!RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$')
                            .hasMatch(value)) {
                          return 'Please enter a valid email';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),

                    // Password Field
                    AppTextField(
                      controller: _passwordController,
                      label: 'Password',
                      hintText: 'Enter your password',
                      obscureText: _obscurePassword,
                      prefixIcon: Icons.lock_outlined,
                      suffixIcon: IconButton(
                        icon: Icon(
                          _obscurePassword
                              ? Icons.visibility_outlined
                              : Icons.visibility_off_outlined,
                        ),
                        onPressed: () {
                          setState(() {
                            _obscurePassword = !_obscurePassword;
                          });
                        },
                      ),
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Please enter your password';
                        }
                        if (value.length < 6) {
                          return 'Password must be at least 6 characters';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 8),

                    // Forgot Password
                    Align(
                      alignment: Alignment.centerRight,
                      child: TextButton(
                        onPressed: () {
                          // Navigate to forgot password
                        },
                        child: const Text('Forgot Password?'),
                      ),
                    ),
                    const SizedBox(height: 24),

                    // Login Button
                    AppButton(
                      onPressed: state is AuthLoading ? null : _onLogin,
                      isLoading: state is AuthLoading,
                      child: const Text('Sign In'),
                    ),
                    const SizedBox(height: 24),

                    // Divider
                    Row(
                      children: [
                        const Expanded(child: Divider()),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 16),
                          child: Text(
                            'Or continue with',
                            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                                  color: AppColors.textSecondary,
                                ),
                          ),
                        ),
                        const Expanded(child: Divider()),
                      ],
                    ),
                    const SizedBox(height: 24),

                    // Social Login Buttons
                    const SocialLoginButtons(),
                    const SizedBox(height: 32),

                    // Register Link
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(
                          "Don't have an account? ",
                          style: Theme.of(context).textTheme.bodyMedium,
                        ),
                        TextButton(
                          onPressed: () => context.push(RouteNames.register),
                          child: const Text('Sign Up'),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          );
        },
      ),
    );
  }
}
```

*(Days 213-220 continue with similar detail for Social Login, Product Browsing, Shopping Cart, Checkout, Orders, Push Notifications, and App Store Preparation)*

---

## 4. Technical Specifications

### 4.1 App Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Presentation Layer                        │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │   Pages     │  │   Widgets   │  │    BLoCs    │         │
│  └─────────────┘  └─────────────┘  └─────────────┘         │
├─────────────────────────────────────────────────────────────┤
│                     Domain Layer                             │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │  Entities   │  │  Use Cases  │  │ Repositories│         │
│  └─────────────┘  └─────────────┘  │ (abstract)  │         │
│                                     └─────────────┘         │
├─────────────────────────────────────────────────────────────┤
│                      Data Layer                              │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │   Models    │  │ Data Sources│  │ Repositories│         │
│  │   (DTOs)    │  │(Remote/Local)│ │   (impl)    │         │
│  └─────────────┘  └─────────────┘  └─────────────┘         │
├─────────────────────────────────────────────────────────────┤
│                      Core Layer                              │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐             │
│  │Theme │ │Router│ │  DI  │ │Network│ │Utils │             │
│  └──────┘ └──────┘ └──────┘ └──────┘ └──────┘             │
└─────────────────────────────────────────────────────────────┘
```

### 4.2 Local Storage Schema (Hive)

| Box Name | Data Type | Purpose | TTL |
|----------|-----------|---------|-----|
| `auth` | AuthData | Tokens, user info | Until logout |
| `products` | List<Product> | Product cache | 1 hour |
| `categories` | List<Category> | Category cache | 24 hours |
| `cart` | CartData | Shopping cart | Until sync |
| `favorites` | List<int> | Wishlist IDs | Until sync |
| `search_history` | List<String> | Recent searches | 30 days |
| `settings` | AppSettings | User preferences | Permanent |

### 4.3 API Integration

| Feature | Endpoint | Cache Strategy |
|---------|----------|----------------|
| Auth | `/api/v1/mobile/auth/*` | No cache |
| Products | `/api/v1/products` | Cache-first, 1h TTL |
| Categories | `/api/v1/categories` | Cache-first, 24h TTL |
| Cart | `/api/v1/cart` | Network-first, offline fallback |
| Orders | `/api/v1/orders` | Network-only |
| Search | `/api/v1/search/*` | Network-only |

### 4.4 Performance Targets

| Metric | Target | Measurement |
|--------|--------|-------------|
| Cold start | < 3s | Firebase Performance |
| Warm start | < 1s | Firebase Performance |
| API response | < 500ms | Dio interceptor |
| Image load | < 1s | CachedNetworkImage |
| Frame rate | 60fps | Flutter DevTools |
| Memory | < 150MB | Android Profiler |
| APK size | < 50MB | build output |
| iOS size | < 70MB | build output |

---

## 5. Testing & Validation

### 5.1 Test Coverage Requirements

| Test Type | Coverage | Tools |
|-----------|----------|-------|
| Unit Tests | > 80% | flutter_test |
| Widget Tests | > 60% | flutter_test |
| BLoC Tests | 100% | bloc_test |
| Integration Tests | Critical paths | integration_test |

### 5.2 Test Cases

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| TC-22-001 | Email login | User authenticated, navigate to home |
| TC-22-002 | Google sign-in | Social token exchanged, user created |
| TC-22-003 | Token refresh | New access token obtained |
| TC-22-004 | Offline product browse | Cached products displayed |
| TC-22-005 | Add to cart | Cart updated, synced to server |
| TC-22-006 | Checkout flow | Order created successfully |
| TC-22-007 | Push notification | Notification received and displayed |

---

## 6. Risk & Mitigation

| Risk ID | Risk | Probability | Impact | Mitigation |
|---------|------|-------------|--------|------------|
| R22-001 | App store rejection | Medium | High | Early compliance review |
| R22-002 | Social login SDK issues | Low | Medium | Version pinning |
| R22-003 | Offline sync conflicts | Medium | Medium | Last-write-wins + manual resolution |
| R22-004 | Performance on low-end devices | Medium | Medium | Lazy loading, pagination |
| R22-005 | Push notification delivery | Low | Medium | FCM topics + direct tokens |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites

- B2C E-Commerce APIs from MS-21
- Firebase project with FCM enabled
- Google/Facebook OAuth apps configured
- App store developer accounts active

### 7.2 Outputs for Subsequent Milestones

| Output | Consumer Milestone | Description |
|--------|-------------------|-------------|
| Mobile SDK patterns | MS-23 (Subscription) | Mobile subscription UI |
| Push notification setup | MS-24 (Loyalty) | Points/rewards notifications |
| Payment integration | MS-25 (Checkout) | Mobile payment methods |
| Offline sync patterns | MS-28 (Farm) | Farm app offline mode |

---

**Document End**

*Milestone 22: Mobile App Foundation — Customer App*
*Smart Dairy Digital Smart Portal + ERP System*
*Phase 3 — E-Commerce, Mobile & Analytics*
*Version 1.0 | February 2026*
