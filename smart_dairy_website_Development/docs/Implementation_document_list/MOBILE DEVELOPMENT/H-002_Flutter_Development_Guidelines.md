# H-002: Flutter Development Guidelines

---

## Document Information

| Field | Details |
|-------|---------|
| **Document ID** | H-002 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Tech Lead |
| **Status** | Approved |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Project Structure](#2-project-structure)
3. [Coding Standards](#3-coding-standards)
4. [State Management](#4-state-management)
5. [Dependency Injection](#5-dependency-injection)
6. [Navigation](#6-navigation)
7. [HTTP Client](#7-http-client)
8. [Local Storage](#8-local-storage)
9. [Theming](#9-theming)
10. [Forms & Validation](#10-forms--validation)
11. [Error Handling](#11-error-handling)
12. [Logging](#12-logging)
13. [Code Generation](#13-code-generation)
14. [Linting & Analysis](#14-linting--analysis)
15. [Asset Management](#15-asset-management)
16. [Platform-Specific Code](#16-platform-specific-code)
17. [Build Configuration](#17-build-configuration)
18. [Appendices](#18-appendices)

---

## 1. Introduction

### 1.1 Flutter Overview

Flutter is Google's UI toolkit for building natively compiled applications for mobile, web, and desktop from a single codebase. Smart Dairy Ltd. adopts Flutter for cross-platform mobile development to ensure consistent user experience across Android and iOS platforms.

### 1.2 Technology Stack

| Component | Version | Purpose |
|-----------|---------|---------|
| Flutter SDK | 3.16+ | UI Framework |
| Dart SDK | 3.2+ | Programming Language |
| Android SDK | API 21+ | Android Support |
| iOS | 12.0+ | iOS Support |

### 1.3 Project Setup

#### 1.3.1 Environment Setup

```bash
# Verify Flutter installation
flutter doctor

# Enable desktop support (optional)
flutter config --enable-windows-desktop
flutter config --enable-macos-desktop
flutter config --enable-linux-desktop

# Enable web support (optional)
flutter config --enable-web
```

#### 1.3.2 Project Initialization

```bash
# Create new project
flutter create --org com.smartdairy \
  --project-name smart_dairy_app \
  --description "Smart Dairy Mobile Application" \
  --platforms android,ios \
  smart_dairy_app

# Navigate to project
cd smart_dairy_app

# Create folder structure
mkdir -p lib/{core,data,domain,presentation,di}
mkdir -p lib/core/{constants,errors,network,theme,utils}
mkdir -p lib/data/{datasources,models,repositories}
mkdir -p lib/domain/{entities,repositories,usecases}
mkdir -p lib/presentation/{bloc,pages,widgets}
mkdir -p test/{unit,widget,integration}
```

#### 1.3.3 Essential Dependencies

```yaml
# pubspec.yaml
dependencies:
  flutter:
    sdk: flutter
  
  # State Management
  flutter_bloc: ^8.1.3
  bloc: ^8.1.2
  
  # Dependency Injection
  get_it: ^7.6.4
  injectable: ^2.3.2
  
  # Navigation
  go_router: ^12.1.1
  
  # HTTP Client
  dio: ^5.4.0
  retrofit: ^4.0.3
  
  # Local Storage
  shared_preferences: ^2.2.2
  flutter_secure_storage: ^9.0.0
  hive: ^2.2.3
  hive_flutter: ^1.1.0
  
  # Code Generation
  freezed_annotation: ^2.4.1
  json_annotation: ^4.8.1
  
  # Forms
  reactive_forms: ^16.1.0
  
  # Theming
  flex_color_scheme: ^7.3.1
  google_fonts: ^6.1.0
  
  # Assets
  flutter_svg: ^2.0.9
  cached_network_image: ^3.3.0
  lottie: ^2.7.0
  
  # Logging
  logger: ^2.0.2
  talker_flutter: ^3.6.0
  
  # Utilities
  equatable: ^2.0.5
  dartz: ^0.10.1
  connectivity_plus: ^5.0.2
  package_info_plus: ^5.0.1
  device_info_plus: ^9.1.1
  
dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.1
  
  # Code Generation
  build_runner: ^2.4.7
  freezed: ^2.4.5
  json_serializable: ^6.7.1
  injectable_generator: ^2.4.1
  retrofit_generator: ^8.0.6
  hive_generator: ^2.0.1
  
  # Testing
  mockito: ^5.4.4
  bloc_test: ^9.1.5
  
flutter:
  uses-material-design: true
```

---

## 2. Project Structure

### 2.1 Feature-First Architecture

Smart Dairy Flutter projects follow a **Feature-First Clean Architecture** pattern. This approach organizes code by feature rather than by layer, promoting better scalability and maintainability.

```
lib/
├── main.dart                          # Application entry point
├── app.dart                           # Root application widget
├── injection.dart                     # Dependency injection setup
├── config/                            # Configuration files
│   ├── routes/                        # Route definitions
│   │   └── app_router.dart
│   ├── theme/                         # Theme configuration
│   │   └── app_theme.dart
│   └── constants/                     # App constants
│       └── app_constants.dart
├── core/                              # Core utilities
│   ├── errors/                        # Error handling
│   │   ├── exceptions.dart
│   │   └── failures.dart
│   ├── network/                       # Network utilities
│   │   ├── dio_client.dart
│   │   └── network_info.dart
│   ├── usecases/                      # Base use case
│   │   └── usecase.dart
│   ├── utils/                         # Utilities
│   │   └── extensions/
│   └── widgets/                       # Common widgets
│       └── loading_indicator.dart
├── features/                          # Feature modules
│   ├── auth/                          # Authentication feature
│   │   ├── data/
│   │   │   ├── datasources/
│   │   │   │   ├── auth_remote_datasource.dart
│   │   │   │   └── auth_local_datasource.dart
│   │   │   ├── models/
│   │   │   │   ├── user_model.dart
│   │   │   │   └── login_response_model.dart
│   │   │   └── repositories/
│   │   │       └── auth_repository_impl.dart
│   │   ├── domain/
│   │   │   ├── entities/
│   │   │   │   └── user.dart
│   │   │   ├── repositories/
│   │   │   │   └── auth_repository.dart
│   │   │   └── usecases/
│   │   │       ├── login.dart
│   │   │       ├── register.dart
│   │   │       └── logout.dart
│   │   └── presentation/
│   │       ├── bloc/
│   │       │   ├── auth_bloc.dart
│   │       │   ├── auth_event.dart
│   │       │   └── auth_state.dart
│   │       ├── pages/
│   │       │   ├── login_page.dart
│   │       │   └── register_page.dart
│   │       └── widgets/
│   │           ├── login_form.dart
│   │           └── auth_button.dart
│   ├── products/                      # Products feature
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   ├── orders/                        # Orders feature
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   └── farm_management/               # Farm management feature
│       ├── data/
│       ├── domain/
│       └── presentation/
└── generated/                         # Generated code
    └── router/
```

### 2.2 Layer Responsibilities

| Layer | Responsibility | Dependencies |
|-------|---------------|--------------|
| **Presentation** | UI, BLoC, Pages | Domain |
| **Domain** | Business logic, Entities, Use cases | None |
| **Data** | Data sources, Models, Repository implementations | Domain |
| **Core** | Shared utilities, Base classes | None |

### 2.3 Data Flow

```
Presentation Layer
       ↓ (triggers)
   Use Case
       ↓ (calls)
   Repository (Abstract)
       ↓ (implemented by)
Repository Implementation
       ↓ (uses)
Data Source (Remote/Local)
       ↓ (returns)
   Model
       ↓ (maps to)
   Entity
```

---

## 3. Coding Standards

### 3.1 Naming Conventions

| Element | Convention | Example |
|---------|------------|---------|
| Files | snake_case | `user_repository.dart` |
| Classes | PascalCase | `UserRepository` |
| Functions/Methods | camelCase | `getUserById` |
| Variables | camelCase | `userName` |
| Constants | lowerCamelCase | `apiBaseUrl` |
| Enums | PascalCase | `OrderStatus` |
| Enum values | lowerCamelCase | `orderStatus.pending` |
| Private members | _prefix | `_userController` |
| BLoC classes | [Feature]Bloc | `AuthBloc` |
| Events | [Feature][Action] | `AuthLoginRequested` |
| States | [Feature][State] | `AuthAuthenticated` |

### 3.2 File Organization

```dart
// 1. Imports (dart, flutter, third-party, local)
import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import 'package:smart_dairy/core/errors/failures.dart';

// 2. Part directives (for generated files)
part 'user_model.g.dart';
part 'user_model.freezed.dart';

// 3. Exports (if barrel file)
export 'auth_repository.dart';

// 4. Main class definition
class UserModel extends User {
  // 5. Static constants
  static const String tableName = 'users';
  
  // 6. Factory constructors
  factory UserModel.fromJson(Map<String, dynamic> json) =>
      _$UserModelFromJson(json);
  
  // 7. Main constructor
  const UserModel({
    required super.id,
    required super.email,
    required super.name,
  });
  
  // 8. Methods
  Map<String, dynamic> toJson() => _$UserModelToJson(this);
}
```

### 3.3 Code Formatting

```yaml
# analysis_options.yaml (summary)
linter:
  rules:
    # Formatting
    lines_longer_than_80_chars: true
    require_trailing_commas: true
    
    # Style
    always_specify_types: false  # Use type inference where obvious
    avoid_dynamic_calls: true
    prefer_single_quotes: true
    prefer_final_locals: true
    prefer_const_constructors: true
```

### 3.4 IDE Configuration

```yaml
# .vscode/settings.json
{
  "editor.formatOnSave": true,
  "editor.rulers": [80],
  "dart.lineLength": 80,
  "dart.previewFlutterUiGuides": true,
  "[dart]": {
    "editor.defaultFormatter": "Dart-Code.dart-code",
    "editor.formatOnSave": true,
    "editor.formatOnType": true,
    "editor.selectionHighlight": false,
    "editor.suggest.snippetsPreventQuickSuggestions": false,
    "editor.suggestSelection": "first",
    "editor.tabCompletion": "onlySnippets",
    "editor.wordBasedSuggestions": "off"
  }
}
```

---

## 4. State Management

### 4.1 BLoC Pattern (Preferred)

Smart Dairy adopts the BLoC (Business Logic Component) pattern for state management. BLoC provides:
- Clear separation of concerns
- Testable business logic
- Reactive programming model
- Predictable state transitions

### 4.2 BLoC Implementation

```dart
// lib/features/auth/presentation/bloc/auth_event.dart
import 'package:equatable/equatable.dart';

abstract class AuthEvent extends Equatable {
  const AuthEvent();

  @override
  List<Object?> get props => [];
}

class AuthLoginRequested extends AuthEvent {
  final String email;
  final String password;

  const AuthLoginRequested({
    required this.email,
    required this.password,
  });

  @override
  List<Object?> get props => [email, password];
}

class AuthLogoutRequested extends AuthEvent {
  const AuthLogoutRequested();
}

class AuthCheckRequested extends AuthEvent {
  const AuthCheckRequested();
}
```

```dart
// lib/features/auth/presentation/bloc/auth_state.dart
import 'package:equatable/equatable.dart';
import 'package:smart_dairy/features/auth/domain/entities/user.dart';

enum AuthStatus { initial, loading, authenticated, unauthenticated, failure }

class AuthState extends Equatable {
  final AuthStatus status;
  final User? user;
  final String? errorMessage;

  const AuthState({
    this.status = AuthStatus.initial,
    this.user,
    this.errorMessage,
  });

  const AuthState.initial() : this(status: AuthStatus.initial);
  const AuthState.loading() : this(status: AuthStatus.loading);
  const AuthState.authenticated(User user)
      : this(status: AuthStatus.authenticated, user: user);
  const AuthState.unauthenticated()
      : this(status: AuthStatus.unauthenticated);
  const AuthState.failure(String message)
      : this(status: AuthStatus.failure, errorMessage: message);

  AuthState copyWith({
    AuthStatus? status,
    User? user,
    String? errorMessage,
  }) {
    return AuthState(
      status: status ?? this.status,
      user: user ?? this.user,
      errorMessage: errorMessage ?? this.errorMessage,
    );
  }

  @override
  List<Object?> get props => [status, user, errorMessage];
}
```

```dart
// lib/features/auth/presentation/bloc/auth_bloc.dart
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:smart_dairy/features/auth/domain/usecases/login.dart';
import 'package:smart_dairy/features/auth/domain/usecases/logout.dart';
import 'package:smart_dairy/features/auth/domain/usecases/check_auth_status.dart';

import 'auth_event.dart';
import 'auth_state.dart';

class AuthBloc extends Bloc<AuthEvent, AuthState> {
  final Login _login;
  final Logout _logout;
  final CheckAuthStatus _checkAuthStatus;

  AuthBloc({
    required Login login,
    required Logout logout,
    required CheckAuthStatus checkAuthStatus,
  })  : _login = login,
        _logout = logout,
        _checkAuthStatus = checkAuthStatus,
        super(const AuthState.initial()) {
    on<AuthCheckRequested>(_onAuthCheckRequested);
    on<AuthLoginRequested>(_onAuthLoginRequested);
    on<AuthLogoutRequested>(_onAuthLogoutRequested);
  }

  Future<void> _onAuthCheckRequested(
    AuthCheckRequested event,
    Emitter<AuthState> emit,
  ) async {
    emit(const AuthState.loading());
    
    final result = await _checkAuthStatus();
    
    result.fold(
      (failure) => emit(const AuthState.unauthenticated()),
      (user) => emit(AuthState.authenticated(user)),
    );
  }

  Future<void> _onAuthLoginRequested(
    AuthLoginRequested event,
    Emitter<AuthState> emit,
  ) async {
    emit(const AuthState.loading());
    
    final result = await _login(
      LoginParams(email: event.email, password: event.password),
    );
    
    result.fold(
      (failure) => emit(AuthState.failure(failure.message)),
      (user) => emit(AuthState.authenticated(user)),
    );
  }

  Future<void> _onAuthLogoutRequested(
    AuthLogoutRequested event,
    Emitter<AuthState> emit,
  ) async {
    emit(const AuthState.loading());
    
    await _logout();
    emit(const AuthState.unauthenticated());
  }
}
```

### 4.3 BLoC Provider Setup

```dart
// lib/app.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:smart_dairy/config/routes/app_router.dart';
import 'package:smart_dairy/config/theme/app_theme.dart';
import 'package:smart_dairy/features/auth/presentation/bloc/auth_bloc.dart';
import 'package:smart_dairy/injection.dart';

class SmartDairyApp extends StatelessWidget {
  const SmartDairyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider(
          create: (_) => getIt<AuthBloc>()..add(const AuthCheckRequested()),
        ),
      ],
      child: MaterialApp.router(
        title: 'Smart Dairy',
        debugShowCheckedModeBanner: false,
        theme: AppTheme.lightTheme,
        darkTheme: AppTheme.darkTheme,
        themeMode: ThemeMode.system,
        routerConfig: getIt<AppRouter>().router,
      ),
    );
  }
}
```

### 4.4 BLoC UI Integration

```dart
// lib/features/auth/presentation/pages/login_page.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import 'package:smart_dairy/features/auth/presentation/bloc/auth_bloc.dart';
import 'package:smart_dairy/features/auth/presentation/bloc/auth_event.dart';
import 'package:smart_dairy/features/auth/presentation/bloc/auth_state.dart';

class LoginPage extends StatelessWidget {
  const LoginPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocListener<AuthBloc, AuthState>(
        listenWhen: (previous, current) =>
            previous.status != current.status,
        listener: (context, state) {
          if (state.status == AuthStatus.authenticated) {
            context.go('/home');
          } else if (state.status == AuthStatus.failure) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text(state.errorMessage ?? 'Login failed')),
            );
          }
        },
        child: const LoginForm(),
      ),
    );
  }
}

class LoginForm extends StatefulWidget {
  const LoginForm({super.key});

  @override
  State<LoginForm> createState() => _LoginFormState();
}

class _LoginFormState extends State<LoginForm> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _onLoginPressed() {
    context.read<AuthBloc>().add(
      AuthLoginRequested(
        email: _emailController.text.trim(),
        password: _passwordController.text,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<AuthBloc, AuthState>(
      builder: (context, state) {
        final isLoading = state.status == AuthStatus.loading;
        
        return Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              TextField(
                controller: _emailController,
                decoration: const InputDecoration(
                  labelText: 'Email',
                  prefixIcon: Icon(Icons.email),
                ),
                keyboardType: TextInputType.emailAddress,
              ),
              const SizedBox(height: 16),
              TextField(
                controller: _passwordController,
                decoration: const InputDecoration(
                  labelText: 'Password',
                  prefixIcon: Icon(Icons.lock),
                ),
                obscureText: true,
              ),
              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: isLoading ? null : _onLoginPressed,
                  child: isLoading
                      ? const CircularProgressIndicator()
                      : const Text('Login'),
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}
```

### 4.5 State Management Comparison

| Approach | Pros | Cons | Use Case |
|----------|------|------|----------|
| **BLoC** | Testable, scalable, clear separation | Boilerplate, learning curve | Complex features, team projects |
| **Riverpod** | Less boilerplate, compile-safe | Different paradigm, newer | Modern projects, simpler state |
| **Provider** | Simple, widely used | Less structured for complex state | Simple state management |
| **GetX** | Minimal boilerplate | Less testable, magic strings | Rapid prototyping |

---

## 5. Dependency Injection

### 5.1 GetIt Configuration

Smart Dairy uses GetIt combined with injectable for type-safe dependency injection.

```dart
// lib/injection.dart
import 'package:get_it/get_it.dart';
import 'package:injectable/injectable.dart';

import 'injection.config.dart';

final getIt = GetIt.instance;

@InjectableInit(
  initializerName: r'$initGetIt',
  preferRelativeImports: true,
  asExtension: false,
)
void configureDependencies() => $initGetIt(getIt);
```

### 5.2 Injectable Setup

```dart
// lib/core/network/dio_client.dart
import 'package:dio/dio.dart';
import 'package:injectable/injectable.dart';
import 'package:smart_dairy/core/constants/app_constants.dart';

@module
abstract class NetworkModule {
  @singleton
  Dio get dio => Dio(
        BaseOptions(
          baseUrl: AppConstants.apiBaseUrl,
          connectTimeout: const Duration(seconds: 30),
          receiveTimeout: const Duration(seconds: 30),
          headers: {'Content-Type': 'application/json'},
        ),
      )..interceptors.addAll([
          // Add interceptors
        ]);
}
```

```dart
// lib/features/auth/data/datasources/auth_remote_datasource.dart
import 'package:injectable/injectable.dart';

abstract class AuthRemoteDataSource {
  Future<Map<String, dynamic>> login(String email, String password);
}

@LazySingleton(as: AuthRemoteDataSource)
class AuthRemoteDataSourceImpl implements AuthRemoteDataSource {
  // Implementation
}
```

```dart
// lib/features/auth/data/repositories/auth_repository_impl.dart
import 'package:injectable/injectable.dart';
import 'package:smart_dairy/features/auth/domain/repositories/auth_repository.dart';

@LazySingleton(as: AuthRepository)
class AuthRepositoryImpl implements AuthRepository {
  // Implementation
}
```

```dart
// lib/features/auth/presentation/bloc/auth_bloc.dart
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:injectable/injectable.dart';

@injectable
class AuthBloc extends Bloc<AuthEvent, AuthState> {
  AuthBloc({
    required Login login,
    required Logout logout,
    required CheckAuthStatus checkAuthStatus,
  }) : super(const AuthState.initial()) {
    // Implementation
  }
}
```

### 5.3 Generated Configuration

```dart
// lib/injection.config.dart (Generated)
// GENERATED CODE - DO NOT MODIFY BY HAND

// ignore_for_file: type=lint
// coverage:ignore-file

// ignore_for_file: no_leading_underscores_for_library_prefixes
import 'package:dio/dio.dart' as _i3;
import 'package:get_it/get_it.dart' as _i1;
import 'package:injectable/injectable.dart' as _i2;

extension GetItInjectableX on _i1.GetIt {
  _i1.GetIt init({
    String? environment,
    _i2.EnvironmentFilter? environmentFilter,
  }) {
    final gh = _i2.GetItHelper(
      this,
      environment,
      environmentFilter,
    );
    final networkModule = _$NetworkModule();
    gh.singleton<_i3.Dio>(networkModule.dio);
    return this;
  }
}

class _$NetworkModule extends _i2.NetworkModule {}
```

### 5.4 Build Command

```bash
# Generate injectable dependencies
flutter pub run build_runner build --delete-conflicting-outputs
```

---

## 6. Navigation

### 6.1 GoRouter Configuration

Smart Dairy uses GoRouter for declarative routing with deep linking support.

```dart
// lib/config/routes/app_router.dart
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:injectable/injectable.dart';
import 'package:smart_dairy/features/auth/presentation/pages/login_page.dart';
import 'package:smart_dairy/features/home/presentation/pages/home_page.dart';
import 'package:smart_dairy/features/products/presentation/pages/product_details_page.dart';

part 'app_router.g.dart';

@singleton
class AppRouter {
  late final GoRouter router;

  AppRouter() {
    router = GoRouter(
      debugLogDiagnostics: true,
      initialLocation: '/',
      routes: $appRoutes,
      redirect: _handleRedirect,
      errorBuilder: (context, state) => const ErrorPage(),
    );
  }

  String? _handleRedirect(BuildContext context, GoRouterState state) {
    // Handle authentication redirects
    // Return null to allow navigation, or path string to redirect
    return null;
  }
}

@TypedGoRoute<HomeRoute>(
  path: '/',
  routes: [
    TypedGoRoute<ProductDetailsRoute>(
      path: 'products/:id',
    ),
  ],
)
class HomeRoute extends GoRouteData {
  const HomeRoute();

  @override
  Widget build(BuildContext context, GoRouterState state) {
    return const HomePage();
  }
}

class ProductDetailsRoute extends GoRouteData {
  final String id;

  const ProductDetailsRoute({required this.id});

  @override
  Widget build(BuildContext context, GoRouterState state) {
    return ProductDetailsPage(productId: id);
  }
}

@TypedGoRoute<LoginRoute>(path: '/login')
class LoginRoute extends GoRouteData {
  const LoginRoute();

  @override
  Widget build(BuildContext context, GoRouterState state) {
    return const LoginPage();
  }
}
```

### 6.2 Deep Linking Setup

#### Android Configuration

```xml
<!-- android/app/src/main/AndroidManifest.xml -->
<activity
    android:name=".MainActivity"
    android:launchMode="singleTask"
    android:exported="true">
    
    <!-- Deep linking -->
    <intent-filter android:autoVerify="true">
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data 
            android:scheme="https"
            android:host="smartdairybd.com"
            android:pathPrefix="/app" />
    </intent-filter>
    
    <!-- Custom scheme -->
    <intent-filter>
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="smartdairy" />
    </intent-filter>
</activity>
```

#### iOS Configuration

```xml
<!-- ios/Runner/Info.plist -->
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleTypeRole</key>
        <string>Editor</string>
        <key>CFBundleURLName</key>
        <string>com.smartdairy.app</string>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>smartdairy</string>
        </array>
    </dict>
</array>

<!-- Associated Domains for Universal Links -->
<key>com.apple.developer.associated-domains</key>
<array>
    <string>applinks:smartdairybd.com</string>
</array>
```

### 6.3 Navigation Patterns

```dart
// Navigate to a route
context.go('/products/123');

// Navigate with parameters
context.push('/products/$productId');

// Replace current route
context.replace('/home');

// Go back
context.pop();

// Using typed routes
const ProductDetailsRoute(id: '123').go(context);
```

---

## 7. HTTP Client

### 7.1 Dio Configuration

```dart
// lib/core/network/dio_client.dart
import 'package:dio/dio.dart';
import 'package:injectable/injectable.dart';
import 'package:smart_dairy/core/constants/app_constants.dart';
import 'package:smart_dairy/core/network/interceptors/auth_interceptor.dart';
import 'package:smart_dairy/core/network/interceptors/logging_interceptor.dart';
import 'package:smart_dairy/core/network/interceptors/error_interceptor.dart';

@module
abstract class DioModule {
  @singleton
  Dio provideDio(
    AuthInterceptor authInterceptor,
    LoggingInterceptor loggingInterceptor,
    ErrorInterceptor errorInterceptor,
  ) {
    final dio = Dio(
      BaseOptions(
        baseUrl: AppConstants.apiBaseUrl,
        connectTimeout: const Duration(seconds: 30),
        receiveTimeout: const Duration(seconds: 30),
        sendTimeout: const Duration(seconds: 30),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ),
    );

    dio.interceptors.addAll([
      authInterceptor,
      loggingInterceptor,
      errorInterceptor,
    ]);

    return dio;
  }
}
```

### 7.2 Interceptors

```dart
// lib/core/network/interceptors/auth_interceptor.dart
import 'package:dio/dio.dart';
import 'package:injectable/injectable.dart';
import 'package:smart_dairy/core/storage/secure_storage.dart';

@injectable
class AuthInterceptor extends Interceptor {
  final SecureStorage _secureStorage;

  AuthInterceptor(this._secureStorage);

  @override
  Future<void> onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    final token = await _secureStorage.getAccessToken();
    
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    
    handler.next(options);
  }

  @override
  Future<void> onError(
    DioException err,
    ErrorInterceptorHandler handler,
  ) async {
    if (err.response?.statusCode == 401) {
      // Handle token refresh or logout
      await _secureStorage.deleteAccessToken();
      // Trigger logout flow
    }
    handler.next(err);
  }
}
```

```dart
// lib/core/network/interceptors/logging_interceptor.dart
import 'package:dio/dio.dart';
import 'package:injectable/injectable.dart';
import 'package:logger/logger.dart';

@injectable
class LoggingInterceptor extends Interceptor {
  final Logger _logger;

  LoggingInterceptor(this._logger);

  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    _logger.i('➡️ REQUEST: ${options.method} ${options.path}');
    _logger.d('Headers: ${options.headers}');
    _logger.d('Data: ${options.data}');
    handler.next(options);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    _logger.i('⬅️ RESPONSE: ${response.statusCode} ${response.requestOptions.path}');
    _logger.d('Data: ${response.data}');
    handler.next(response);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    _logger.e('❌ ERROR: ${err.requestOptions.path}');
    _logger.e('Status: ${err.response?.statusCode}');
    _logger.e('Message: ${err.message}');
    handler.next(err);
  }
}
```

```dart
// lib/core/network/interceptors/error_interceptor.dart
import 'package:dio/dio.dart';
import 'package:injectable/injectable.dart';
import 'package:smart_dairy/core/errors/exceptions.dart';

@injectable
class ErrorInterceptor extends Interceptor {
  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    final exception = _mapDioErrorToException(err);
    handler.reject(
      DioException(
        requestOptions: err.requestOptions,
        error: exception,
        type: err.type,
        response: err.response,
      ),
    );
  }

  Exception _mapDioErrorToException(DioException error) {
    switch (error.type) {
      case DioExceptionType.connectionTimeout:
      case DioExceptionType.sendTimeout:
      case DioExceptionType.receiveTimeout:
        return TimeoutException();
      case DioExceptionType.badResponse:
        return _handleResponseError(error.response);
      case DioExceptionType.connectionError:
        return NetworkException();
      default:
        return UnknownException();
    }
  }

  Exception _handleResponseError(Response? response) {
    if (response == null) return UnknownException();
    
    switch (response.statusCode) {
      case 400:
        return BadRequestException(response.data['message']);
      case 401:
        return UnauthorizedException();
      case 403:
        return ForbiddenException();
      case 404:
        return NotFoundException();
      case 500:
        return ServerException();
      default:
        return UnknownException();
    }
  }
}
```

### 7.3 Error Handling

```dart
// lib/core/errors/exceptions.dart
class ServerException implements Exception {
  final String? message;
  ServerException([this.message]);
}

class NetworkException implements Exception {}

class TimeoutException implements Exception {}

class UnauthorizedException implements Exception {}

class ForbiddenException implements Exception {}

class NotFoundException implements Exception {}

class BadRequestException implements Exception {
  final String? message;
  BadRequestException([this.message]);
}

class UnknownException implements Exception {}
```

```dart
// lib/core/errors/failures.dart
import 'package:equatable/equatable.dart';

abstract class Failure extends Equatable {
  final String message;

  const Failure(this.message);

  @override
  List<Object?> get props => [message];
}

class ServerFailure extends Failure {
  const ServerFailure([String message = 'Server error occurred'])
      : super(message);
}

class NetworkFailure extends Failure {
  const NetworkFailure([String message = 'Please check your internet connection'])
      : super(message);
}

class TimeoutFailure extends Failure {
  const TimeoutFailure([String message = 'Request timeout'])
      : super(message);
}

class AuthFailure extends Failure {
  const AuthFailure([String message = 'Authentication failed'])
      : super(message);
}

class CacheFailure extends Failure {
  const CacheFailure([String message = 'Cache error'])
      : super(message);
}
```

### 7.4 Retrofit API Service

```dart
// lib/features/auth/data/datasources/auth_api_service.dart
import 'package:dio/dio.dart';
import 'package:retrofit/retrofit.dart';
import 'package:injectable/injectable.dart';

import '../models/login_request_model.dart';
import '../models/login_response_model.dart';

part 'auth_api_service.g.dart';

@RestApi()
@injectable
abstract class AuthApiService {
  @factoryMethod
  factory AuthApiService(Dio dio) = _AuthApiService;

  @POST('/auth/login')
  Future<LoginResponseModel> login(@Body() LoginRequestModel request);

  @POST('/auth/register')
  Future<LoginResponseModel> register(@Body() RegisterRequestModel request);

  @POST('/auth/refresh')
  Future<TokenRefreshResponse> refreshToken(
    @Body() TokenRefreshRequest request,
  );

  @POST('/auth/logout')
  Future<void> logout();
}
```

---

## 8. Local Storage

### 8.1 Secure Storage Pattern

```dart
// lib/core/storage/secure_storage.dart
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:injectable/injectable.dart';

@singleton
class SecureStorage {
  static const _accessTokenKey = 'access_token';
  static const _refreshTokenKey = 'refresh_token';
  static const _userIdKey = 'user_id';

  final FlutterSecureStorage _storage;

  SecureStorage() 
      : _storage = const FlutterSecureStorage(
          aOptions: AndroidOptions(
            encryptedSharedPreferences: true,
          ),
          iOptions: IOSOptions(
            accountName: 'smart_dairy_secure_storage',
          ),
        );

  Future<void> setAccessToken(String token) async {
    await _storage.write(key: _accessTokenKey, value: token);
  }

  Future<String?> getAccessToken() async {
    return _storage.read(key: _accessTokenKey);
  }

  Future<void> deleteAccessToken() async {
    await _storage.delete(key: _accessTokenKey);
  }

  Future<void> setRefreshToken(String token) async {
    await _storage.write(key: _refreshTokenKey, value: token);
  }

  Future<String?> getRefreshToken() async {
    return _storage.read(key: _refreshTokenKey);
  }

  Future<void> clearAll() async {
    await _storage.deleteAll();
  }
}
```

### 8.2 SharedPreferences Pattern

```dart
// lib/core/storage/preferences_storage.dart
import 'package:injectable/injectable.dart';
import 'package:shared_preferences/shared_preferences.dart';

@singleton
class PreferencesStorage {
  late final SharedPreferences _prefs;

  Future<void> init() async {
    _prefs = await SharedPreferences.getInstance();
  }

  // Theme
  Future<void> setThemeMode(String mode) async {
    await _prefs.setString('theme_mode', mode);
  }

  String? getThemeMode() {
    return _prefs.getString('theme_mode');
  }

  // Language
  Future<void> setLanguage(String languageCode) async {
    await _prefs.setString('language_code', languageCode);
  }

  String getLanguage() {
    return _prefs.getString('language_code') ?? 'en';
  }

  // Notifications
  Future<void> setNotificationsEnabled(bool enabled) async {
    await _prefs.setBool('notifications_enabled', enabled);
  }

  bool getNotificationsEnabled() {
    return _prefs.getBool('notifications_enabled') ?? true;
  }

  // Onboarding
  Future<void> setOnboardingCompleted(bool completed) async {
    await _prefs.setBool('onboarding_completed', completed);
  }

  bool getOnboardingCompleted() {
    return _prefs.getBool('onboarding_completed') ?? false;
  }

  // Clear all
  Future<void> clear() async {
    await _prefs.clear();
  }
}
```

### 8.3 Hive Local Database

```dart
// lib/core/storage/hive_storage.dart
import 'package:hive_flutter/hive_flutter.dart';
import 'package:injectable/injectable.dart';

@singleton
class HiveStorage {
  static const String userBox = 'user_box';
  static const String productsBox = 'products_box';
  static const String ordersBox = 'orders_box';

  Future<void> init() async {
    await Hive.initFlutter();
    
    // Register adapters
    Hive.registerAdapter(UserModelAdapter());
    Hive.registerAdapter(ProductModelAdapter());
    Hive.registerAdapter(OrderModelAdapter());

    // Open boxes
    await Hive.openBox<UserModel>(userBox);
    await Hive.openBox<ProductModel>(productsBox);
    await Hive.openBox<OrderModel>(ordersBox);
  }

  Box<UserModel> get userBoxInstance => Hive.box<UserModel>(userBox);
  Box<ProductModel> get productsBoxInstance => Hive.box<ProductModel>(productsBox);
  Box<OrderModel> get ordersBoxInstance => Hive.box<OrderModel>(ordersBox);
}
```

---

## 9. Theming

### 9.1 Theme Configuration

```dart
// lib/config/theme/app_theme.dart
import 'package:flex_color_scheme/flex_color_scheme.dart';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AppTheme {
  // Brand Colors - Smart Dairy
  static const Color primaryColor = Color(0xFF2E7D32);     // Dairy Green
  static const Color secondaryColor = Color(0xFF8D6E63);   // Earth Brown
  static const Color accentColor = Color(0xFFFFB74D);      // Warm Orange
  static const Color errorColor = Color(0xFFD32F2F);       // Error Red
  static const Color surfaceColor = Color(0xFFF5F5F5);     // Light Surface
  
  static ThemeData get lightTheme {
    return FlexThemeData.light(
      scheme: FlexScheme.green,
      primary: primaryColor,
      secondary: secondaryColor,
      appBarStyle: FlexAppBarStyle.primaryBackground,
      appBarElevation: 0,
      transparentStatusBar: true,
      tabBarStyle: FlexTabBarStyle.forBackground,
      tooltipsMatchBackground: true,
      swapColors: true,
      lightIsWhite: true,
      useMaterial3: true,
      useMaterial3ErrorColors: true,
      subThemesData: const FlexSubThemesData(
        useTextTheme: true,
        useM2StyleDividerInM3: true,
        filledButtonRadius: 8.0,
        elevatedButtonRadius: 8.0,
        outlinedButtonRadius: 8.0,
        inputDecoratorRadius: 8.0,
        inputDecoratorUnfocusedHasBorder: true,
        inputDecoratorBorderType: FlexInputBorderType.outline,
        fabRadius: 16.0,
        chipRadius: 8.0,
        cardRadius: 12.0,
        popupMenuRadius: 8.0,
        dialogRadius: 16.0,
        timePickerDialogRadius: 16.0,
        bottomSheetRadius: 16.0,
      ),
    ).copyWith(
      textTheme: GoogleFonts.nunitoTextTheme(),
      splashFactory: InkSparkle.splashFactory,
    );
  }

  static ThemeData get darkTheme {
    return FlexThemeData.dark(
      scheme: FlexScheme.green,
      primary: primaryColor,
      secondary: secondaryColor,
      appBarStyle: FlexAppBarStyle.background,
      appBarElevation: 0,
      transparentStatusBar: true,
      tabBarStyle: FlexTabBarStyle.forBackground,
      tooltipsMatchBackground: true,
      swapColors: true,
      darkIsTrueBlack: false,
      useMaterial3: true,
      useMaterial3ErrorColors: true,
      subThemesData: const FlexSubThemesData(
        useTextTheme: true,
        useM2StyleDividerInM3: true,
        filledButtonRadius: 8.0,
        elevatedButtonRadius: 8.0,
        outlinedButtonRadius: 8.0,
        inputDecoratorRadius: 8.0,
        inputDecoratorUnfocusedHasBorder: true,
        inputDecoratorBorderType: FlexInputBorderType.outline,
        fabRadius: 16.0,
        chipRadius: 8.0,
        cardRadius: 12.0,
        popupMenuRadius: 8.0,
        dialogRadius: 16.0,
        timePickerDialogRadius: 16.0,
        bottomSheetRadius: 16.0,
      ),
    ).copyWith(
      textTheme: GoogleFonts.nunitoTextTheme(ThemeData.dark().textTheme),
      splashFactory: InkSparkle.splashFactory,
    );
  }
}
```

### 9.2 Custom Theme Extensions

```dart
// lib/config/theme/app_theme_extensions.dart
import 'package:flutter/material.dart';

@immutable
class SmartDairyColors extends ThemeExtension<SmartDairyColors> {
  final Color success;
  final Color warning;
  final Color info;
  final Color milkWhite;
  final Color grassGreen;
  final Color earthBrown;

  const SmartDairyColors({
    required this.success,
    required this.warning,
    required this.info,
    required this.milkWhite,
    required this.grassGreen,
    required this.earthBrown,
  });

  @override
  SmartDairyColors copyWith({
    Color? success,
    Color? warning,
    Color? info,
    Color? milkWhite,
    Color? grassGreen,
    Color? earthBrown,
  }) {
    return SmartDairyColors(
      success: success ?? this.success,
      warning: warning ?? this.warning,
      info: info ?? this.info,
      milkWhite: milkWhite ?? this.milkWhite,
      grassGreen: grassGreen ?? this.grassGreen,
      earthBrown: earthBrown ?? this.earthBrown,
    );
  }

  @override
  SmartDairyColors lerp(ThemeExtension<SmartDairyColors>? other, double t) {
    if (other is! SmartDairyColors) return this;
    return SmartDairyColors(
      success: Color.lerp(success, other.success, t)!,
      warning: Color.lerp(warning, other.warning, t)!,
      info: Color.lerp(info, other.info, t)!,
      milkWhite: Color.lerp(milkWhite, other.milkWhite, t)!,
      grassGreen: Color.lerp(grassGreen, other.grassGreen, t)!,
      earthBrown: Color.lerp(earthBrown, other.earthBrown, t)!,
    );
  }
}

// Usage in theme
theme: ThemeData.light().copyWith(
  extensions: [
    const SmartDairyColors(
      success: Color(0xFF4CAF50),
      warning: Color(0xFFFF9800),
      info: Color(0xFF2196F3),
      milkWhite: Color(0xFFFFFDE7),
      grassGreen: Color(0xFF66BB6A),
      earthBrown: Color(0xFF8D6E63),
    ),
  ],
),
```

---

## 10. Forms & Validation

### 10.1 Reactive Forms

```dart
// lib/features/auth/presentation/widgets/login_form.dart
import 'package:flutter/material.dart';
import 'package:reactive_forms/reactive_forms.dart';

class LoginForm extends StatefulWidget {
  final VoidCallback onSubmit;

  const LoginForm({super.key, required this.onSubmit});

  @override
  State<LoginForm> createState() => _LoginFormState();
}

class _LoginFormState extends State<LoginForm> {
  final form = FormGroup({
    'email': FormControl<String>(
      validators: [
        Validators.required,
        Validators.email,
      ],
    ),
    'password': FormControl<String>(
      validators: [
        Validators.required,
        Validators.minLength(8),
      ],
    ),
    'rememberMe': FormControl<bool>(value: false),
  });

  @override
  void dispose() {
    form.dispose();
    super.dispose();
  }

  void _submit() {
    if (form.valid) {
      widget.onSubmit();
    } else {
      form.markAllAsTouched();
    }
  }

  @override
  Widget build(BuildContext context) {
    return ReactiveForm(
      formGroup: form,
      child: Column(
        children: [
          ReactiveTextField<String>(
            formControlName: 'email',
            decoration: const InputDecoration(
              labelText: 'Email',
              prefixIcon: Icon(Icons.email),
            ),
            validationMessages: {
              ValidationMessage.required: (_) => 'Email is required',
              ValidationMessage.email: (_) => 'Enter a valid email',
            },
            keyboardType: TextInputType.emailAddress,
          ),
          const SizedBox(height: 16),
          ReactiveTextField<String>(
            formControlName: 'password',
            decoration: const InputDecoration(
              labelText: 'Password',
              prefixIcon: Icon(Icons.lock),
            ),
            validationMessages: {
              ValidationMessage.required: (_) => 'Password is required',
              ValidationMessage.minLength: (_) => 
                  'Password must be at least 8 characters',
            },
            obscureText: true,
          ),
          const SizedBox(height: 8),
          ReactiveCheckboxListTile(
            formControlName: 'rememberMe',
            title: const Text('Remember me'),
          ),
          const SizedBox(height: 24),
          ReactiveFormConsumer(
            builder: (context, form, child) {
              return ElevatedButton(
                onPressed: form.valid ? _submit : null,
                child: const Text('Login'),
              );
            },
          ),
        ],
      ),
    );
  }
}
```

### 10.2 Custom Validators

```dart
// lib/core/utils/validators/app_validators.dart
import 'package:reactive_forms/reactive_forms.dart';

class AppValidators {
  static Map<String, String> Function(AbstractControl<dynamic>)? 
      get bangladeshPhoneValidationMessages => {
            ValidationMessage.required: 'Phone number is required',
            ValidationMessage.pattern: 'Enter a valid Bangladeshi phone number',
          };

  static Validator<dynamic> get bangladeshPhone {
    return Validators.pattern(
      r'^(?:\+88|88)?(01[3-9]\d{8})$',
    );
  }

  static Validator<dynamic> get strongPassword {
    return Validators.compose([
      Validators.required,
      Validators.minLength(8),
      Validators.pattern(
        r'^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$',
      ),
    ]);
  }

  static Validator<dynamic> matchPassword(String controlName) {
    return (AbstractControl<dynamic> control) {
      final form = control as FormGroup;
      final password = form.control(controlName).value as String?;
      final confirmPassword = control.value as String?;

      if (password != confirmPassword) {
        return {'passwordMismatch': true};
      }
      return null;
    };
  }
}
```

---

## 11. Error Handling

### 11.1 Try-Catch Patterns

```dart
// lib/core/utils/error_handler.dart
import 'dart:async';

import 'package:smart_dairy/core/errors/failures.dart';

typedef Result<T> = Future<Either<Failure, T>>;

abstract class ErrorHandler {
  static Future<Either<Failure, T>> handle<T>(
    Future<T> Function() operation,
  ) async {
    try {
      final result = await operation();
      return Right(result);
    } on ServerException catch (e) {
      return Left(ServerFailure(e.message));
    } on NetworkException {
      return const Left(NetworkFailure());
    } on TimeoutException {
      return const Left(TimeoutFailure());
    } on UnauthorizedException {
      return const Left(AuthFailure('Session expired'));
    } catch (e) {
      return Left(ServerFailure(e.toString()));
    }
  }
}
```

### 11.2 Error Boundaries

```dart
// lib/core/widgets/error_boundary.dart
import 'package:flutter/material.dart';
import 'package:logger/logger.dart';

class ErrorBoundary extends StatefulWidget {
  final Widget child;
  final Widget Function(FlutterErrorDetails)? errorBuilder;

  const ErrorBoundary({
    super.key,
    required this.child,
    this.errorBuilder,
  });

  @override
  State<ErrorBoundary> createState() => _ErrorBoundaryState();
}

class _ErrorBoundaryState extends State<ErrorBoundary> {
  FlutterErrorDetails? _error;

  @override
  void initState() {
    super.initState();
    FlutterError.onError = _handleFlutterError;
  }

  void _handleFlutterError(FlutterErrorDetails details) {
    Logger().e('Flutter Error', error: details.exception, stackTrace: details.stack);
    setState(() => _error = details);
  }

  @override
  Widget build(BuildContext context) {
    if (_error != null) {
      return widget.errorBuilder?.call(_error!) ?? _defaultErrorWidget();
    }
    return widget.child;
  }

  Widget _defaultErrorWidget() {
    return Material(
      child: Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(Icons.error_outline, size: 64, color: Colors.red),
            const SizedBox(height: 16),
            const Text(
              'Something went wrong',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            ElevatedButton(
              onPressed: () => setState(() => _error = null),
              child: const Text('Retry'),
            ),
          ],
        ),
      ),
    );
  }
}
```

---

## 12. Logging

### 12.1 Logger Configuration

```dart
// lib/core/utils/logger.dart
import 'package:injectable/injectable.dart';
import 'package:logger/logger.dart';
import 'package:talker_flutter/talker_flutter.dart';

@singleton
class AppLogger {
  late final Logger _logger;
  late final Talker _talker;

  AppLogger() {
    _logger = Logger(
      printer: PrettyPrinter(
        methodCount: 2,
        errorMethodCount: 8,
        lineLength: 120,
        colors: true,
        printEmojis: true,
        dateTimeFormat: DateTimeFormat.dateAndTime,
      ),
    );

    _talker = TalkerFlutter.init(
      settings: TalkerSettings(
        enabled: true,
        useHistory: true,
        maxHistoryItems: 100,
      ),
    );
  }

  void debug(String message, [dynamic error, StackTrace? stackTrace]) {
    _logger.d(message, error: error, stackTrace: stackTrace);
    _talker.debug(message, error, stackTrace);
  }

  void info(String message, [dynamic error, StackTrace? stackTrace]) {
    _logger.i(message, error: error, stackTrace: stackTrace);
    _talker.info(message, error, stackTrace);
  }

  void warning(String message, [dynamic error, StackTrace? stackTrace]) {
    _logger.w(message, error: error, stackTrace: stackTrace);
    _talker.warning(message, error, stackTrace);
  }

  void error(String message, [dynamic error, StackTrace? stackTrace]) {
    _logger.e(message, error: error, stackTrace: stackTrace);
    _talker.error(message, error, stackTrace);
  }

  void verbose(String message, [dynamic error, StackTrace? stackTrace]) {
    _logger.t(message, error: error, stackTrace: stackTrace);
    _talker.verbose(message, error, stackTrace);
  }

  Talker get talker => _talker;
}
```

### 12.2 BLoC Observer

```dart
// lib/core/utils/bloc_observer.dart
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:injectable/injectable.dart';
import 'package:smart_dairy/core/utils/logger.dart';

@singleton
class AppBlocObserver extends BlocObserver {
  final AppLogger _logger;

  AppBlocObserver(this._logger);

  @override
  void onCreate(BlocBase bloc) {
    super.onCreate(bloc);
    _logger.info('BLoC Created: ${bloc.runtimeType}');
  }

  @override
  void onEvent(Bloc bloc, Object? event) {
    super.onEvent(bloc, event);
    _logger.debug('Event: ${bloc.runtimeType}, $event');
  }

  @override
  void onChange(BlocBase bloc, Change change) {
    super.onChange(bloc, change);
    _logger.debug(
      'State Change: ${bloc.runtimeType}\n'
      'From: ${change.currentState}\n'
      'To: ${change.nextState}',
    );
  }

  @override
  void onTransition(Bloc bloc, Transition transition) {
    super.onTransition(bloc, transition);
    _logger.verbose(
      'Transition: ${bloc.runtimeType}\n'
      'Event: ${transition.event}\n'
      'From: ${transition.currentState}\n'
      'To: ${transition.nextState}',
    );
  }

  @override
  void onError(BlocBase bloc, Object error, StackTrace stackTrace) {
    super.onError(bloc, error, stackTrace);
    _logger.error(
      'BLoC Error: ${bloc.runtimeType}',
      error,
      stackTrace,
    );
  }

  @override
  void onClose(BlocBase bloc) {
    super.onClose(bloc);
    _logger.info('BLoC Closed: ${bloc.runtimeType}');
  }
}
```

### 12.3 Main.dart Setup

```dart
// lib/main.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:smart_dairy/app.dart';
import 'package:smart_dairy/core/utils/bloc_observer.dart';
import 'package:smart_dairy/injection.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  // Configure dependencies
  configureDependencies();
  
  // Set BLoC observer
  Bloc.observer = getIt<AppBlocObserver>();
  
  runApp(const ErrorBoundary(child: SmartDairyApp()));
}
```

---

## 13. Code Generation

### 13.1 Freezed Data Classes

```dart
// lib/features/products/data/models/product_model.dart
import 'package:freezed_annotation/freezed_annotation.dart';

import '../../domain/entities/product.dart';

part 'product_model.freezed.dart';
part 'product_model.g.dart';

@freezed
class ProductModel with _$ProductModel {
  const factory ProductModel({
    required String id,
    required String name,
    required String description,
    required double price,
    required String category,
    required String imageUrl,
    @Default(false) bool isOrganic,
    @Default(0) int stockQuantity,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) = _ProductModel;

  factory ProductModel.fromJson(Map<String, dynamic> json) =>
      _$ProductModelFromJson(json);

  const ProductModel._();

  Product toEntity() => Product(
        id: id,
        name: name,
        description: description,
        price: price,
        category: category,
        imageUrl: imageUrl,
        isOrganic: isOrganic,
        stockQuantity: stockQuantity,
      );

  factory ProductModel.fromEntity(Product entity) => ProductModel(
        id: entity.id,
        name: entity.name,
        description: entity.description,
        price: entity.price,
        category: entity.category,
        imageUrl: entity.imageUrl,
        isOrganic: entity.isOrganic,
        stockQuantity: entity.stockQuantity,
      );
}
```

### 13.2 JsonSerializable

```dart
// lib/features/auth/data/models/login_response_model.dart
import 'package:json_annotation/json_annotation.dart';

import 'user_model.dart';

part 'login_response_model.g.dart';

@JsonSerializable()
class LoginResponseModel {
  @JsonKey(name: 'access_token')
  final String accessToken;
  
  @JsonKey(name: 'refresh_token')
  final String refreshToken;
  
  @JsonKey(name: 'expires_in')
  final int expiresIn;
  
  final UserModel user;

  LoginResponseModel({
    required this.accessToken,
    required this.refreshToken,
    required this.expiresIn,
    required this.user,
  });

  factory LoginResponseModel.fromJson(Map<String, dynamic> json) =>
      _$LoginResponseModelFromJson(json);

  Map<String, dynamic> toJson() => _$LoginResponseModelToJson(this);
}
```

### 13.3 Build Commands

```bash
# Build all generated files
flutter pub run build_runner build

# Build and delete conflicting outputs
flutter pub run build_runner build --delete-conflicting-outputs

# Watch for changes (continuous build)
flutter pub run build_runner watch

# Watch and delete conflicting outputs
flutter pub run build_runner watch --delete-conflicting-outputs
```

---

## 14. Linting & Analysis

### 14.1 analysis_options.yaml

```yaml
# analysis_options.yaml
include: package:flutter_lints/flutter.yaml

analyzer:
  exclude:
    - "**/*.g.dart"
    - "**/*.freezed.dart"
    - "**/*.config.dart"
    - "**/generated_plugin_registrant.dart"
    - "**/.dart_tool/**"
    - "**/build/**"

  language:
    strict-casts: true
    strict-raw-types: true
    strict-inference: true

  errors:
    invalid_annotation_target: ignore
    mixin_inherits_from_not_object: error
    missing_return: error
    dead_code: info
    unused_local_variable: warning
    todo: ignore

linter:
  rules:
    # Style rules
    - always_declare_return_types
    - always_put_control_body_on_new_line
    - always_put_required_named_parameters_first
    - always_require_non_null_named_parameters
    - always_specify_types: false
    - annotate_overrides
    - avoid_bool_literals_in_conditional_expressions
    - avoid_catches_without_on_clauses
    - avoid_catching_errors
    - avoid_classes_with_only_static_members
    - avoid_double_and_int_checks
    - avoid_dynamic_calls
    - avoid_empty_else
    - avoid_equals_and_hash_code_on_mutable_classes
    - avoid_escaping_inner_quotes
    - avoid_field_initializers_in_const_classes
    - avoid_function_literals_in_foreach_calls
    - avoid_implementing_value_types
    - avoid_init_to_null
    - avoid_js_rounded_ints
    - avoid_null_checks_in_equality_operators
    - avoid_positional_boolean_parameters
    - avoid_print
    - avoid_private_typedef_functions
    - avoid_redundant_argument_values
    - avoid_relative_lib_imports
    - avoid_renaming_method_parameters
    - avoid_return_types_on_setters
    - avoid_returning_null
    - avoid_returning_null_for_future
    - avoid_returning_null_for_void
    - avoid_returning_this
    - avoid_setters_without_getters
    - avoid_shadowing_type_parameters
    - avoid_single_cascade_in_expression_statements
    - avoid_slow_async_io
    - avoid_type_to_string
    - avoid_types_as_parameter_names
    - avoid_types_on_closure_parameters: false
    - avoid_unnecessary_containers
    - avoid_unused_constructor_parameters
    - avoid_void_async
    - avoid_web_libraries_in_flutter
    - await_only_futures
    - camel_case_extensions
    - camel_case_types
    - cancel_subscriptions
    - cascade_invocations
    - cast_nullable_to_non_nullable
    - close_sinks
    - comment_references
    - constant_identifier_names
    - control_flow_in_finally
    - curly_braces_in_flow_control_structures
    - depend_on_referenced_packages
    - deprecated_consistency
    - directives_ordering
    - empty_catches
    - empty_constructor_bodies
    - empty_statements
    - exhaustive_cases
    - file_names
    - hash_and_equals
    - implementation_imports
    - invariant_booleans
    - iterable_contains_unrelated_type
    - join_return_with_assignment
    - leading_newlines_in_multiline_strings
    - library_names
    - library_prefixes
    - library_private_types_in_public_api
    - lines_longer_than_80_chars: true
    - list_remove_unrelated_type
    - literal_only_boolean_expressions
    - missing_whitespace_between_adjacent_strings
    - no_adjacent_strings_in_list
    - no_duplicate_case_values
    - no_logic_in_create_state
    - no_runtimeType_toString
    - non_constant_identifier_names
    - null_check_on_nullable_type_parameter
    - null_closures
    - omit_local_variable_types: false
    - one_member_abstracts
    - only_throw_errors
    - overridden_fields
    - package_api_docs
    - package_names
    - package_prefixed_library_names
    - parameter_assignments
    - prefer_adjacent_string_concatenation
    - prefer_asserts_in_initializer_lists
    - prefer_asserts_with_message
    - prefer_collection_literals
    - prefer_conditional_assignment
    - prefer_const_constructors
    - prefer_const_constructors_in_immutables
    - prefer_const_declarations
    - prefer_const_literals_to_create_immutables
    - prefer_constructors_over_static_methods
    - prefer_contains
    - prefer_double_quotes: false
    - prefer_equal_for_default_values
    - prefer_expression_function_bodies
    - prefer_final_fields
    - prefer_final_in_for_each
    - prefer_final_locals
    - prefer_final_parameters
    - prefer_for_elements_to_map_fromIterable
    - prefer_foreach
    - prefer_function_declarations_over_variables
    - prefer_generic_function_type_aliases
    - prefer_if_elements_to_conditional_expressions
    - prefer_if_null_operators
    - prefer_initializing_formals
    - prefer_inlined_adds
    - prefer_int_literals
    - prefer_interpolation_to_compose_strings
    - prefer_is_empty
    - prefer_is_not_empty
    - prefer_is_not_operator
    - prefer_iterable_whereType
    - prefer_null_aware_operators
    - prefer_relative_imports
    - prefer_single_quotes: true
    - prefer_spread_collections
    - prefer_typing_uninitialized_variables
    - prefer_void_to_null
    - provide_deprecation_message
    - public_member_api_docs: false
    - recursive_getters
    - require_trailing_commas: true
    - sized_box_for_whitespace
    - slash_for_doc_comments
    - sort_child_properties_last
    - sort_constructors_first
    - sort_pub_dependencies
    - sort_unnamed_constructors_first
    - test_types_in_equals
    - throw_in_finally
    - tighten_type_of_initializing_formals
    - type_annotate_public_apis
    - type_init_formals
    - unawaited_futures
    - unnecessary_await_in_return
    - unnecessary_brace_in_string_interps
    - unnecessary_const
    - unnecessary_getters_setters
    - unnecessary_lambdas
    - unnecessary_new
    - unnecessary_null_aware_assignments
    - unnecessary_null_checks
    - unnecessary_null_in_if_null_operators
    - unnecessary_nullable_for_final_variable_declarations
    - unnecessary_overrides
    - unnecessary_parenthesis
    - unnecessary_raw_strings
    - unnecessary_statements
    - unnecessary_string_escapes
    - unnecessary_string_interpolations
    - unnecessary_this
    - unrelated_type_equality_checks
    - unsafe_html
    - use_build_context_synchronously
    - use_colored_box
    - use_decorated_box
    - use_full_hex_values_for_flutter_colors
    - use_function_type_syntax_for_parameters
    - use_if_null_to_convert_nulls_to_bools
    - use_is_even_rather_than_modulo
    - use_key_in_widget_constructors
    - use_late_for_private_fields_and_variables
    - use_named_constants
    - use_raw_strings
    - use_rethrow_when_possible
    - use_setters_to_change_properties
    - use_string_buffers
    - use_test_throws_matchers
    - use_to_and_as_if_applicable
    - valid_regexps
    - void_checks

dart_code_metrics:
  metrics:
    cyclomatic-complexity: 20
    number-of-parameters: 4
    maximum-nesting-level: 5
    source-lines-of-code: 50
  rules:
    - avoid-dynamic
    - avoid-passing-async-when-sync-expected
    - avoid-redundant-async
    - avoid-unnecessary-type-assertions
    - avoid-unnecessary-type-casts
    - avoid-unrelated-type-assertions
    - avoid-unused-parameters
    - newline-before-return
    - no-boolean-literal-compare
    - no-empty-block
    - prefer-const-border-radius
    - prefer-correct-type-name
    - prefer-first
    - prefer-immediate-return
    - prefer-last
    - prefer-match-file-name
  anti-patterns:
    - long-method
    - long-parameter-list
```

---

## 15. Asset Management

### 15.1 Asset Structure

```
assets/
├── images/
│   ├── logo.png
│   ├── splash.png
│   ├── onboarding/
│   │   ├── slide1.png
│   │   ├── slide2.png
│   │   └── slide3.png
│   └── products/
│       ├── milk.png
│       ├── yogurt.png
│       └── cheese.png
├── icons/
│   ├── home.svg
│   ├── cart.svg
│   ├── profile.svg
│   └── farm.svg
├── animations/
│   ├── loading.json
│   ├── success.json
│   └── error.json
├── fonts/
│   └── custom/
└── translations/
    ├── en.json
    └── bn.json
```

### 15.2 pubspec.yaml Configuration

```yaml
flutter:
  uses-material-design: true

  assets:
    - assets/images/
    - assets/images/onboarding/
    - assets/images/products/
    - assets/icons/
    - assets/animations/
    - assets/translations/

  fonts:
    - family: Nunito
      fonts:
        - asset: assets/fonts/nunito/Nunito-Regular.ttf
        - asset: assets/fonts/nunito/Nunito-Italic.ttf
          style: italic
        - asset: assets/fonts/nunito/Nunito-Bold.ttf
          weight: 700
        - asset: assets/fonts/nunito/Nunito-SemiBold.ttf
          weight: 600
```

### 15.3 Asset Optimization

```bash
# Image optimization (using flutter_image_compress)
# Android: Place images in drawable-xxhdpi for best quality
# iOS: Use @2x and @3x variants

# SVG optimization
flutter_svg: ^2.0.9

# Lottie animations
lottie: ^2.7.0

# Image caching
cached_network_image: ^3.3.0
```

### 15.4 Asset Helper

```dart
// lib/core/constants/app_assets.dart
class AppAssets {
  // Private constructor to prevent instantiation
  AppAssets._();

  // Images
  static const String logo = 'assets/images/logo.png';
  static const String splash = 'assets/images/splash.png';
  
  // Onboarding
  static const String onboarding1 = 'assets/images/onboarding/slide1.png';
  static const String onboarding2 = 'assets/images/onboarding/slide2.png';
  static const String onboarding3 = 'assets/images/onboarding/slide3.png';
  
  // Icons
  static const String homeIcon = 'assets/icons/home.svg';
  static const String cartIcon = 'assets/icons/cart.svg';
  static const String profileIcon = 'assets/icons/profile.svg';
  static const String farmIcon = 'assets/icons/farm.svg';
  
  // Animations
  static const String loading = 'assets/animations/loading.json';
  static const String success = 'assets/animations/success.json';
  static const String error = 'assets/animations/error.json';
}
```

---

## 16. Platform-Specific Code

### 16.1 Method Channels

```dart
// lib/core/platform/platform_channel.dart
import 'package:flutter/services.dart';
import 'package:injectable/injectable.dart';

@singleton
class PlatformChannel {
  static const MethodChannel _channel =
      MethodChannel('com.smartdairy.app/channel');

  Future<String?> getBatteryLevel() async {
    try {
      final String? batteryLevel =
          await _channel.invokeMethod('getBatteryLevel');
      return batteryLevel;
    } on PlatformException catch (e) {
      throw 'Failed to get battery level: ${e.message}';
    }
  }

  Future<void> shareContent(String text) async {
    try {
      await _channel.invokeMethod('shareContent', {'text': text});
    } on PlatformException catch (e) {
      throw 'Failed to share: ${e.message}';
    }
  }
}
```

### 16.2 Platform Checks

```dart
// lib/core/utils/platform_utils.dart
import 'dart:io';

import 'package:flutter/foundation.dart';

class PlatformUtils {
  static bool get isMobile => !kIsWeb && (Platform.isIOS || Platform.isAndroid);
  static bool get isDesktop =>
      !kIsWeb && (Platform.isMacOS || Platform.isWindows || Platform.isLinux);
  static bool get isWeb => kIsWeb;
  static bool get isAndroid => !kIsWeb && Platform.isAndroid;
  static bool get isIOS => !kIsWeb && Platform.isIOS;
}
```

---

## 17. Build Configuration

### 17.1 Flavor Setup

```dart
// lib/config/flavor_config.dart
import 'package:flutter/material.dart';

enum Flavor { dev, staging, prod }

class FlavorConfig {
  final Flavor flavor;
  final String name;
  final String apiBaseUrl;
  final bool showDebugBanner;

  static FlavorConfig? _instance;

  factory FlavorConfig({
    required Flavor flavor,
    required String apiBaseUrl,
  }) {
    _instance ??= FlavorConfig._internal(
      flavor: flavor,
      name: flavor.name,
      apiBaseUrl: apiBaseUrl,
      showDebugBanner: flavor == Flavor.dev,
    );
    return _instance!;
  }

  FlavorConfig._internal({
    required this.flavor,
    required this.name,
    required this.apiBaseUrl,
    required this.showDebugBanner,
  });

  static FlavorConfig get instance => _instance!;

  static bool isDev() => _instance?.flavor == Flavor.dev;
  static bool isStaging() => _instance?.flavor == Flavor.staging;
  static bool isProd() => _instance?.flavor == Flavor.prod;
}
```

### 17.2 Entry Points

```dart
// lib/main_dev.dart
import 'config/flavor_config.dart';
import 'main.dart' as main_app;

void main() {
  FlavorConfig(
    flavor: Flavor.dev,
    apiBaseUrl: 'https://api-dev.smartdairybd.com/v1',
  );
  main_app.main();
}
```

```dart
// lib/main_staging.dart
import 'config/flavor_config.dart';
import 'main.dart' as main_app;

void main() {
  FlavorConfig(
    flavor: Flavor.staging,
    apiBaseUrl: 'https://api-staging.smartdairybd.com/v1',
  );
  main_app.main();
}
```

```dart
// lib/main_prod.dart
import 'config/flavor_config.dart';
import 'main.dart' as main_app;

void main() {
  FlavorConfig(
    flavor: Flavor.prod,
    apiBaseUrl: 'https://api.smartdairybd.com/v1',
  );
  main_app.main();
}
```

### 17.3 Android Build Configuration

```gradle
// android/app/build.gradle
android {
    ...
    flavorDimensions "env"
    
    productFlavors {
        dev {
            dimension "env"
            applicationIdSuffix ".dev"
            versionNameSuffix "-dev"
            resValue "string", "app_name", "Smart Dairy Dev"
        }
        staging {
            dimension "env"
            applicationIdSuffix ".staging"
            versionNameSuffix "-staging"
            resValue "string", "app_name", "Smart Dairy Staging"
        }
        prod {
            dimension "env"
            resValue "string", "app_name", "Smart Dairy"
        }
    }
}
```

### 17.4 iOS Build Configuration

```ruby
# ios/Runner/Info.plist
# Use different bundle identifiers per flavor

# ios/Flutter/Debug-dev.xcconfig
#include? "Pods/Target Support Files/Pods-Runner/Pods-Runner.debug-dev.xcconfig"
#include "Generated.xcconfig"
FLUTTER_TARGET=lib/main_dev.dart
```

### 17.5 CI-Ready Build Commands

```bash
#!/bin/bash
# scripts/build.sh

set -e

FLAVOR=$1
BUILD_TYPE=$2

if [ -z "$FLAVOR" ] || [ -z "$BUILD_TYPE" ]; then
    echo "Usage: ./build.sh <dev|staging|prod> <apk|appbundle|ipa>"
    exit 1
fi

# Clean build
flutter clean
flutter pub get

# Generate code
flutter pub run build_runner build --delete-conflicting-outputs

# Run tests
flutter test

# Build based on flavor and type
case "$BUILD_TYPE" in
    apk)
        flutter build apk --flavor $FLAVOR -t lib/main_$FLAVOR.dart
        ;;
    appbundle)
        flutter build appbundle --flavor $FLAVOR -t lib/main_$FLAVOR.dart
        ;;
    ipa)
        flutter build ipa --flavor $FLAVOR -t lib/main_$FLAVOR.dart
        ;;
    *)
        echo "Invalid build type. Use: apk, appbundle, or ipa"
        exit 1
        ;;
esac

echo "Build completed successfully!"
```

```yaml
# .github/workflows/flutter_ci.yml
name: Flutter CI

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main, develop]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
      - run: flutter pub get
      - run: flutter pub run build_runner build --delete-conflicting-outputs
      - run: flutter analyze
      - run: flutter test
      - run: flutter build apk --flavor dev -t lib/main_dev.dart
```

---

## 18. Appendices

### Appendix A: Sample Code Repository

```
lib/
├── main.dart
├── app.dart
├── config/
│   ├── flavor_config.dart
│   ├── routes/
│   │   └── app_router.dart
│   └── theme/
│       └── app_theme.dart
├── core/
│   ├── constants/
│   │   ├── app_assets.dart
│   │   └── app_constants.dart
│   ├── errors/
│   │   ├── exceptions.dart
│   │   └── failures.dart
│   ├── network/
│   │   ├── dio_client.dart
│   │   └── interceptors/
│   │       ├── auth_interceptor.dart
│   │       ├── error_interceptor.dart
│   │       └── logging_interceptor.dart
│   ├── storage/
│   │   ├── secure_storage.dart
│   │   └── preferences_storage.dart
│   ├── usecases/
│   │   └── usecase.dart
│   └── utils/
│       ├── extensions/
│       ├── logger.dart
│       └── validators/
├── features/
│   └── auth/
│       ├── data/
│       │   ├── datasources/
│       │   │   ├── auth_api_service.dart
│       │   │   ├── auth_local_datasource.dart
│       │   │   └── auth_remote_datasource.dart
│       │   ├── models/
│       │   │   ├── login_request_model.dart
│       │   │   ├── login_response_model.dart
│       │   │   └── user_model.dart
│       │   └── repositories/
│       │       └── auth_repository_impl.dart
│       ├── domain/
│       │   ├── entities/
│       │   │   └── user.dart
│       │   ├── repositories/
│       │   │   └── auth_repository.dart
│       │   └── usecases/
│       │       ├── check_auth_status.dart
│       │       ├── login.dart
│       │       └── logout.dart
│       └── presentation/
│           ├── bloc/
│           │   ├── auth_bloc.dart
│           │   ├── auth_event.dart
│           │   └── auth_state.dart
│           ├── pages/
│           │   └── login_page.dart
│           └── widgets/
│               └── login_form.dart
└── injection.dart
```

### Appendix B: Troubleshooting

| Issue | Solution |
|-------|----------|
| `build_runner` fails | Run `flutter clean` then `flutter pub get` |
| Generated files not updating | Use `--delete-conflicting-outputs` flag |
| iOS build fails | Run `cd ios && pod install --repo-update` |
| Android build fails | Check `minSdkVersion` is 21+ |
| Dependency conflicts | Use `dependency_overrides` temporarily |
| Hot reload not working | Check for const constructors |
| BLoC events not firing | Ensure proper event registration |

### Appendix C: Resources

#### Documentation
- [Flutter Official Docs](https://docs.flutter.dev)
- [Dart Language Tour](https://dart.dev/guides/language/language-tour)
- [BLoC Pattern](https://bloclibrary.dev)
- [GoRouter](https://pub.dev/packages/go_router)

#### Recommended Packages
| Category | Package | Version |
|----------|---------|---------|
| State Management | flutter_bloc | ^8.1.3 |
| DI | get_it, injectable | ^7.6.4 |
| Navigation | go_router | ^12.1.1 |
| HTTP | dio, retrofit | ^5.4.0 |
| Storage | hive, flutter_secure_storage | ^2.2.3 |
| Code Gen | freezed, json_serializable | ^2.4.5 |

#### Community
- [Flutter Community](https://flutter.dev/community)
- [Flutter Bangladesh](https://facebook.com/groups/flutterbangladesh)
- [Stack Overflow - Flutter](https://stackoverflow.com/questions/tagged/flutter)

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Mobile Lead | Initial Release |

---

*This document is proprietary to Smart Dairy Ltd. Unauthorized distribution is prohibited.*
