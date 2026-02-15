# H-013: Mobile Testing Guidelines

## Smart Dairy Ltd - Smart Web Portal System

---

**Document Information**

| Field | Value |
|-------|-------|
| **Document ID** | H-013 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Lead |
| **Reviewer** | Mobile Lead |
| **Status** | Draft |
| **Classification** | Internal |

---

## Document Control

| Version | Date | Author | Changes | Approved By |
|---------|------|--------|---------|-------------|
| 1.0 | January 31, 2026 | QA Engineer | Initial release | - |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Unit Testing](#2-unit-testing)
3. [Widget Testing](#3-widget-testing)
4. [Integration Testing](#4-integration-testing)
5. [Test Organization](#5-test-organization)
6. [Test Data](#6-test-data)
7. [Test Environment](#7-test-environment)
8. [CI Integration](#8-ci-integration)
9. [Coverage Requirements](#9-coverage-requirements)
10. [Device Testing](#10-device-testing)
11. [Manual Testing Checklist](#11-manual-testing-checklist)
12. [Performance Testing](#12-performance-testing)
13. [Accessibility Testing](#13-accessibility-testing)
14. [Security Testing](#14-security-testing)
15. [Beta Testing](#15-beta-testing)
16. [Test Reporting](#16-test-reporting)
17. [Appendices](#17-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes comprehensive testing guidelines for the Smart Dairy mobile application, ensuring high-quality, reliable, and maintainable code through systematic testing practices.

### 1.2 Scope

These guidelines apply to:
- All Flutter mobile application code
- Backend API integration testing
- UI/UX component validation
- End-to-end user flows
- Performance and security validation

### 1.3 Testing Pyramid

```
                    /\
                   /  \
                  / E2E\         Integration Tests (10%)
                 /______\
                /        \
               /  Widget  \      Widget Tests (20%)
              /____________\
             /              \
            /     Unit       \    Unit Tests (70%)
           /__________________\
```

### 1.4 Testing Stack Overview

| Layer | Tools | Purpose |
|-------|-------|---------|
| **Unit Tests** | `flutter_test`, `mockito`, `bloc_test` | Business logic, repositories, BLoCs |
| **Widget Tests** | `flutter_test`, `golden_toolkit` | UI components, interactions |
| **Integration Tests** | `integration_test`, `patrol` | End-to-end flows |
| **Coverage** | `lcov`, `codecov` | Code coverage reporting |
| **CI/CD** | GitHub Actions, Codemagic | Automated execution |

### 1.5 Definitions

| Term | Definition |
|------|------------|
| **AAA Pattern** | Arrange-Act-Assert test structure |
| **BLoC** | Business Logic Component pattern |
| **Golden File** | Reference image for visual regression |
| **PO Pattern** | Page Object pattern for UI tests |
| **MASVS** | Mobile Application Security Verification Standard |

---

## 2. Unit Testing

### 2.1 Test Structure (AAA Pattern)

All unit tests must follow the Arrange-Act-Assert pattern:

```dart
// test/domain/usecases/get_milk_production_test.dart
group('GetMilkProductionUseCase', () {
  late GetMilkProductionUseCase useCase;
  late MockMilkProductionRepository mockRepository;

  setUp(() {
    mockRepository = MockMilkProductionRepository();
    useCase = GetMilkProductionUseCase(mockRepository);
  });

  test('should return milk production data when repository call succeeds', () async {
    // ARRANGE
    const tStartDate = '2026-01-01';
    const tEndDate = '2026-01-31';
    final tMilkProductionList = [
      MilkProduction(
        date: DateTime(2026, 1, 15),
        morningYield: 125.5,
        eveningYield: 118.3,
        cowCount: 45,
      ),
    ];

    when(mockRepository.getProductionRange(any, any))
        .thenAnswer((_) async => Right(tMilkProductionList));

    // ACT
    final result = await useCase(
      GetMilkProductionParams(startDate: tStartDate, endDate: tEndDate),
    );

    // ASSERT
    expect(result, Right(tMilkProductionList));
    verify(mockRepository.getProductionRange(tStartDate, tEndDate));
    verifyNoMoreInteractions(mockRepository);
  });

  test('should return ServerFailure when repository call fails', () async {
    // ARRANGE
    when(mockRepository.getProductionRange(any, any))
        .thenAnswer((_) async => Left(ServerFailure()));

    // ACT
    final result = await useCase(
      GetMilkProductionParams(startDate: '2026-01-01', endDate: '2026-01-31'),
    );

    // ASSERT
    expect(result, Left(ServerFailure()));
  });
});
```

### 2.2 Mocking with Mockito

#### 2.2.1 Setup

Add to `pubspec.yaml`:

```yaml
dev_dependencies:
  flutter_test:
    sdk: flutter
  mockito: ^5.4.4
  build_runner: ^2.4.7
```

#### 2.2.2 Mock Generation

Create `test/helpers/test_helpers.dart`:

```dart
import 'package:mockito/annotations.dart';
import 'package:smart_dairy/domain/repositories/milk_production_repository.dart';
import 'package:smart_dairy/domain/repositories/auth_repository.dart';
import 'package:smart_dairy/domain/repositories/cattle_repository.dart';

@GenerateMocks([
  MilkProductionRepository,
  AuthRepository,
  CattleRepository,
])
void main() {}
```

Generate mocks:

```bash
flutter pub run build_runner build --delete-conflicting-outputs
```

#### 2.2.3 Mock Best Practices

```dart
// ✅ GOOD: Clear arrange section with specific values
test('validates input correctly', () {
  // Arrange
  const validEmail = 'farmer@smartdairy.com';
  const invalidEmail = 'invalid-email';
  
  // Act & Assert
  expect(EmailValidator.isValid(validEmail), true);
  expect(EmailValidator.isValid(invalidEmail), false);
});

// ❌ BAD: Unclear test data
.test('test validation', () {
  expect(EmailValidator.isValid('test'), false);
});

// ✅ GOOD: Verify interactions
verify(mockRepository.login(any)).called(1);
verifyNoMoreInteractions(mockRepository);

// ✅ GOOD: Use matchers appropriately
when(mockRepository.getCattle(any))
    .thenAnswer((_) async => Right(anyListOfCattle));
```

### 2.3 BLoC Testing with bloc_test

#### 2.3.1 Setup

```yaml
dev_dependencies:
  bloc_test: ^9.1.5
  mocktail: ^1.0.1  # Alternative to mockito for BLoC tests
```

#### 2.3.2 BLoC Test Examples

```dart
// test/presentation/bloc/milk_production_bloc_test.dart
import 'package:bloc_test/bloc_test.dart';
import 'package:smart_dairy/presentation/bloc/milk_production/milk_production_bloc.dart';

class MockGetMilkProduction extends Mock implements GetMilkProductionUseCase {}

void main() {
  late MockGetMilkProduction mockGetMilkProduction;
  late MilkProductionBloc bloc;

  setUp(() {
    mockGetMilkProduction = MockGetMilkProduction();
    bloc = MilkProductionBloc(getMilkProduction: mockGetMilkProduction);
  });

  tearDown(() {
    bloc.close();
  });

  group('LoadMilkProduction', () {
    final tMilkProductionList = [
      MilkProduction(
        date: DateTime(2026, 1, 15),
        morningYield: 125.5,
        eveningYield: 118.3,
      ),
    ];

    blocTest<MilkProductionBloc, MilkProductionState>(
      'emits [Loading, Loaded] when data is fetched successfully',
      build: () {
        when(() => mockGetMilkProduction(any()))
            .thenAnswer((_) async => Right(tMilkProductionList));
        return bloc;
      },
      act: (bloc) => bloc.add(const LoadMilkProduction(
        startDate: '2026-01-01',
        endDate: '2026-01-31',
      )),
      expect: () => [
        MilkProductionLoading(),
        MilkProductionLoaded(production: tMilkProductionList),
      ],
      verify: (_) {
        verify(() => mockGetMilkProduction(any())).called(1);
      },
    );

    blocTest<MilkProductionBloc, MilkProductionState>(
      'emits [Loading, Error] when data fetch fails',
      build: () {
        when(() => mockGetMilkProduction(any()))
            .thenAnswer((_) async => Left(ServerFailure()));
        return bloc;
      },
      act: (bloc) => bloc.add(const LoadMilkProduction(
        startDate: '2026-01-01',
        endDate: '2026-01-31',
      )),
      expect: () => [
        MilkProductionLoading(),
        const MilkProductionError(message: 'Failed to load milk production'),
      ],
    );

    blocTest<MilkProductionBloc, MilkProductionState>(
      'emits [Loading, Empty] when no data is available',
      build: () {
        when(() => mockGetMilkProduction(any()))
            .thenAnswer((_) async => const Right([]));
        return bloc;
      },
      act: (bloc) => bloc.add(const LoadMilkProduction(
        startDate: '2026-01-01',
        endDate: '2026-01-31',
      )),
      expect: () => [
        MilkProductionLoading(),
        MilkProductionEmpty(),
      ],
    );
  });

  group('RefreshMilkProduction', () {
    blocTest<MilkProductionBloc, MilkProductionState>(
      'does not emit Loading state on refresh',
      build: () {
        when(() => mockGetMilkProduction(any()))
            .thenAnswer((_) async => Right([]));
        return bloc;
      },
      seed: () => MilkProductionLoaded(production: []),
      act: (bloc) => bloc.add(const RefreshMilkProduction()),
      expect: () => [
        MilkProductionLoaded(production: []),
      ],
    );
  });
}
```

#### 2.3.3 Repository Testing

```dart
// test/data/repositories/auth_repository_impl_test.dart
group('AuthRepositoryImpl', () {
  late AuthRepositoryImpl repository;
  late MockAuthRemoteDataSource mockRemoteDataSource;
  late MockNetworkInfo mockNetworkInfo;
  late MockAuthLocalDataSource mockLocalDataSource;

  setUp(() {
    mockRemoteDataSource = MockAuthRemoteDataSource();
    mockNetworkInfo = MockNetworkInfo();
    mockLocalDataSource = MockAuthLocalDataSource();
    repository = AuthRepositoryImpl(
      remoteDataSource: mockRemoteDataSource,
      localDataSource: mockLocalDataSource,
      networkInfo: mockNetworkInfo,
    );
  });

  group('login', () {
    const tEmail = 'farmer@smartdairy.com';
    const tPassword = 'securePass123';
    final tAuthModel = AuthModel(
      token: 'jwt_token_123',
      userId: 'user_123',
      farmId: 'farm_456',
    );

    test('should check internet connectivity first', () async {
      // Arrange
      when(mockNetworkInfo.isConnected).thenAnswer((_) async => true);
      when(mockRemoteDataSource.login(any, any))
          .thenAnswer((_) async => tAuthModel);

      // Act
      await repository.login(tEmail, tPassword);

      // Assert
      verify(mockNetworkInfo.isConnected);
    });

    test('should return AuthEntity when remote call succeeds', () async {
      // Arrange
      when(mockNetworkInfo.isConnected).thenAnswer((_) async => true);
      when(mockRemoteDataSource.login(any, any))
          .thenAnswer((_) async => tAuthModel);
      when(mockLocalDataSource.cacheAuth(any))
          .thenAnswer((_) async => Future.value());

      // Act
      final result = await repository.login(tEmail, tPassword);

      // Assert
      expect(result, Right(tAuthModel));
      verify(mockRemoteDataSource.login(tEmail, tPassword));
      verify(mockLocalDataSource.cacheAuth(tAuthModel));
    });

    test('should return NetworkFailure when offline', () async {
      // Arrange
      when(mockNetworkInfo.isConnected).thenAnswer((_) async => false);

      // Act
      final result = await repository.login(tEmail, tPassword);

      // Assert
      expect(result, Left(NetworkFailure()));
      verifyZeroInteractions(mockRemoteDataSource);
    });
  });
});
```

---

## 3. Widget Testing

### 3.1 Finding Widgets

```dart
// test/presentation/widgets/milk_production_card_test.dart
import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:smart_dairy/presentation/widgets/milk_production_card.dart';

void main() {
  group('MilkProductionCard', () {
    testWidgets('displays correct production data', (WidgetTester tester) async {
      // Arrange
      const production = MilkProduction(
        date: DateTime(2026, 1, 15),
        morningYield: 125.5,
        eveningYield: 118.3,
        totalYield: 243.8,
      );

      // Act
      await tester.pumpWidget(
        const MaterialApp(
          home: Scaffold(
            body: MilkProductionCard(production: production),
          ),
        ),
      );

      // Assert - Finding widgets
      expect(find.byType(MilkProductionCard), findsOneWidget);
      expect(find.text('243.8 L'), findsOneWidget);
      expect(find.textContaining('Morning: 125.5'), findsOneWidget);
      expect(find.textContaining('Evening: 118.3'), findsOneWidget);
    });

    testWidgets('finds widgets using keys', (WidgetTester tester) async {
      await tester.pumpWidget(
        MaterialApp(
          home: Scaffold(
            body: MilkProductionCard(
              production: production,
              key: const Key('production_card'),
            ),
          ),
        ),
      );

      expect(find.byKey(const Key('production_card')), findsOneWidget);
    });

    testWidgets('finds widgets using type', (WidgetTester tester) async {
      await tester.pumpWidget(/* ... */);

      expect(find.byType(Card), findsOneWidget);
      expect(find.byType(Column), findsOneWidget);
      expect(find.byIcon(Icons.water_drop), findsOneWidget);
    });

    testWidgets('finds widgets using text patterns', (WidgetTester tester) async {
      await tester.pumpWidget(/* ... */);

      expect(find.text('Total Production'), findsOneWidget);
      expect(find.textContaining('L'), findsNWidgets(3));
      expect(find.widgetWithText(ElevatedButton, 'View Details'), findsOneWidget);
    });
  });
}
```

### 3.2 User Interactions

```dart
// test/presentation/pages/login_page_test.dart
group('LoginPage Interactions', () {
  testWidgets('validates email on submit', (WidgetTester tester) async {
    await tester.pumpWidget(const MaterialApp(home: LoginPage()));

    // Find and interact with form fields
    final emailField = find.byKey(const Key('email_field'));
    final passwordField = find.byKey(const Key('password_field'));
    final submitButton = find.byKey(const Key('login_button'));

    // Enter text
    await tester.enterText(emailField, 'invalid-email');
    await tester.enterText(passwordField, 'password123');
    
    // Tap submit
    await tester.tap(submitButton);
    await tester.pump(); // Rebuild after state change

    // Verify validation error
    expect(find.text('Please enter a valid email'), findsOneWidget);
  });

  testWidgets('submits form with valid data', (WidgetTester tester) async {
    await tester.pumpWidget(
      BlocProvider<AuthBloc>(
        create: (_) => mockAuthBloc,
        child: const MaterialApp(home: LoginPage()),
      ),
    );

    // Enter valid credentials
    await tester.enterText(
      find.byKey(const Key('email_field')),
      'farmer@smartdairy.com',
    );
    await tester.enterText(
      find.byKey(const Key('password_field')),
      'SecurePass123!',
    );

    // Submit form
    await tester.tap(find.byKey(const Key('login_button')));
    await tester.pump();

    // Verify bloc event was added
    verify(() => mockAuthBloc.add(any(that: isA<LoginSubmitted>()))).called(1);
  });

  testWidgets('scrolls to view widget', (WidgetTester tester) async {
    await tester.pumpWidget(const MaterialApp(home: LongFormPage()));

    final saveButton = find.byKey(const Key('save_button'));
    
    // Scroll until visible
    await tester.scrollUntilVisible(
      saveButton,
      500.0,
      scrollable: find.byType(Scrollable),
    );

    expect(saveButton, findsOneWidget);
  });

  testWidgets('handles long press', (WidgetTester tester) async {
    await tester.pumpWidget(const MaterialApp(home: CattleListPage()));

    await tester.longPress(find.text('Cow #123'));
    await tester.pumpAndSettle();

    expect(find.byType(AlertDialog), findsOneWidget);
    expect(find.text('Delete Cow?'), findsOneWidget);
  });

  testWidgets('handles drag gestures', (WidgetTester tester) async {
    await tester.pumpWidget(const MaterialApp(home: MilkEntryList()));

    // Swipe to dismiss
    await tester.drag(find.byType(Dismissible).first, const Offset(-500, 0));
    await tester.pumpAndSettle();

    expect(find.text('Entry deleted'), findsOneWidget);
  });
});
```

### 3.3 Golden File Testing

```dart
// test/presentation/widgets/golden/milk_production_card_golden_test.dart
import 'package:golden_toolkit/golden_toolkit.dart';

void main() {
  testGoldens('MilkProductionCard renders correctly', (tester) async {
    final builder = GoldenBuilder.grid(
      columns: 2,
      widthToHeightRatio: 1,
    )
      ..addScenario(
        'Default',
        MilkProductionCard(production: defaultProduction),
      )
      ..addScenario(
        'High Production',
        MilkProductionCard(production: highProduction),
      )
      ..addScenario(
        'Low Production',
        MilkProductionCard(production: lowProduction),
      )
      ..addScenario(
        'Loading State',
        const MilkProductionCard.loading(),
      );

    await tester.pumpWidgetBuilder(
      builder.build(),
      wrapper: materialAppWrapper(
        theme: SmartDairyTheme.lightTheme,
      ),
    );

    await screenMatchesGolden(tester, 'milk_production_card');
  });

  testGoldens('LoginPage golden test', (tester) async {
    await tester.pumpWidgetBuilder(
      const LoginPage(),
      wrapper: materialAppWrapper(
        theme: SmartDairyTheme.lightTheme,
      ),
    );

    await screenMatchesGolden(tester, 'login_page');
  });

  testGoldens('Multi-device golden test', (tester) async {
    final widget = MilkProductionDashboard(
      production: sampleProduction,
    );

    await tester.pumpWidgetBuilder(widget);

    await multiScreenGolden(
      tester,
      'milk_production_dashboard',
      devices: [
        Device.phone,
        Device.iphone11,
        Device.tabletPortrait,
        Device.tabletLandscape,
      ],
    );
  });
}
```

#### 3.3.1 Golden File Configuration

Add to `flutter_test_config.dart`:

```dart
import 'dart:async';
import 'package:golden_toolkit/golden_toolkit.dart';

Future<void> testExecutable(FutureOr<void> Function() testMain) async {
  return GoldenToolkit.runWithConfiguration(
    () async {
      await loadAppFonts();
      await testMain();
    },
    config: GoldenToolkitConfiguration(
      enableRealShadows: true,
      defaultDevices: const [
        Device.phone,
        Device.iphone11,
        Device.tabletPortrait,
      ],
    ),
  );
}
```

#### 3.3.2 Update Golden Files

```bash
# Update all golden files
flutter test --update-goldens

# Update specific golden file
flutter test test/presentation/widgets/golden/ --update-goldens
```

---

## 4. Integration Testing

### 4.1 Integration Test Package Setup

```yaml
# pubspec.yaml
dev_dependencies:
  integration_test:
    sdk: flutter
  patrol: ^3.0.0  # Optional but recommended
```

### 4.2 Page Object Pattern

```dart
// integration_test/pages/base_page.dart
abstract class BasePage {
  final WidgetTester tester;
  BasePage(this.tester);

  Future<void> waitFor(Duration duration) async {
    await tester.pumpAndSettle(duration);
  }

  Future<void> takeScreenshot(String name) async {
    await binding.takeScreenshot(name);
  }
}

// integration_test/pages/login_page.dart
class LoginPage extends BasePage {
  LoginPage(WidgetTester tester) : super(tester);

  final _emailField = find.byKey(const Key('email_field'));
  final _passwordField = find.byKey(const Key('password_field'));
  final _loginButton = find.byKey(const Key('login_button'));
  final _errorMessage = find.byKey(const Key('error_message'));

  Future<void> enterEmail(String email) async {
    await tester.enterText(_emailField, email);
    await tester.pump();
  }

  Future<void> enterPassword(String password) async {
    await tester.enterText(_passwordField, password);
    await tester.pump();
  }

  Future<void> tapLogin() async {
    await tester.tap(_loginButton);
    await tester.pumpAndSettle();
  }

  Future<HomePage> loginWithCredentials(String email, String password) async {
    await enterEmail(email);
    await enterPassword(password);
    await tapLogin();
    return HomePage(tester);
  }

  Future<void> verifyErrorMessage(String message) async {
    expect(find.text(message), findsOneWidget);
  }
}

// integration_test/pages/home_page.dart
class HomePage extends BasePage {
  HomePage(WidgetTester tester) : super(tester);

  final _productionCard = find.byKey(const Key('daily_production_card'));
  final _addMilkButton = find.byKey(const Key('add_milk_button'));
  final _menuButton = find.byTooltip('Open navigation menu');

  Future<void> verifyProductionCardVisible() async {
    expect(_productionCard, findsOneWidget);
  }

  Future<AddMilkPage> navigateToAddMilk() async {
    await tester.tap(_addMilkButton);
    await tester.pumpAndSettle();
    return AddMilkPage(tester);
  }

  Future<DrawerMenu> openMenu() async {
    await tester.tap(_menuButton);
    await tester.pumpAndSettle();
    return DrawerMenu(tester);
  }
}

// integration_test/pages/drawer_menu.dart
class DrawerMenu extends BasePage {
  DrawerMenu(WidgetTester tester) : super(tester);

  final _cattleOption = find.text('Cattle Management');
  final _reportsOption = find.text('Reports');
  final _settingsOption = find.text('Settings');

  Future<CattleListPage> navigateToCattle() async {
    await tester.tap(_cattleOption);
    await tester.pumpAndSettle();
    return CattleListPage(tester);
  }
}
```

### 4.3 Integration Test Scenarios

```dart
// integration_test/app_test.dart
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:smart_dairy/main.dart' as app;

import 'pages/login_page.dart';
import 'pages/home_page.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('End-to-End Tests', () {
    testWidgets('complete login flow', (WidgetTester tester) async {
      // Launch app
      app.main();
      await tester.pumpAndSettle();

      // Login
      final loginPage = LoginPage(tester);
      final homePage = await loginPage.loginWithCredentials(
        'test@smartdairy.com',
        'TestPass123!',
      );

      // Verify on home page
      await homePage.verifyProductionCardVisible();
    });

    testWidgets('add milk production entry', (WidgetTester tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Login
      final loginPage = LoginPage(tester);
      final homePage = await loginPage.loginWithCredentials(
        'test@smartdairy.com',
        'TestPass123!',
      );

      // Navigate to add milk
      final addMilkPage = await homePage.navigateToAddMilk();
      
      // Fill form
      await addMilkPage.selectSession('Morning');
      await addMilkPage.enterQuantity(125.5);
      await addMilkPage.selectCattleGroup('Group A');
      await addMilkPage.saveEntry();

      // Verify success
      await addMilkPage.verifySuccessMessage('Entry saved successfully');
    });

    testWidgets('generate and view report', (WidgetTester tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Login
      final loginPage = LoginPage(tester);
      final homePage = await loginPage.loginWithCredentials(
        'test@smartdairy.com',
        'TestPass123!',
      );

      // Navigate to reports
      final menu = await homePage.openMenu();
      final reportsPage = await menu.navigateToReports();

      // Generate monthly report
      await reportsPage.selectReportType('Monthly Production');
      await reportsPage.selectMonth('January 2026');
      await reportsPage.generateReport();

      // Verify report displays
      await reportsPage.verifyReportDisplayed();
    });

    testWidgets('offline mode - create entry', (WidgetTester tester) async {
      // Enable offline mode
      await tester.binding.setNetworkEnabled(false);

      app.main();
      await tester.pumpAndSettle();

      // Login (should use cached credentials)
      final loginPage = LoginPage(tester);
      final homePage = await loginPage.loginWithCredentials(
        'test@smartdairy.com',
        'TestPass123!',
      );

      // Create entry offline
      final addMilkPage = await homePage.navigateToAddMilk();
      await addMilkPage.enterQuantity(100.0);
      await addMilkPage.saveEntry();

      // Verify pending sync indicator
      await addMilkPage.verifyPendingSyncIndicator();

      // Restore connectivity
      await tester.binding.setNetworkEnabled(true);
      await tester.pumpAndSettle(const Duration(seconds: 3));

      // Verify sync success
      await addMilkPage.verifySyncSuccess();
    });
  });
}
```

### 4.4 Patrol Integration (Optional)

```dart
// integration_test/patrol_test.dart
import 'package:patrol/patrol.dart';

void main() {
  patrolTest(
    'taps on buttons and enters text',
    nativeAutomation: true,
    ($) async {
      await $.pumpWidgetAndSettle(const SmartDairyApp());

      await $(#email_field).enterText('test@smartdairy.com');
      await $(#password_field).enterText('password123');
      await $(#login_button).tap();

      // Native interactions
      await $.native.tap(Selector(text: 'Allow'));
      await $.native.enterTextByIndex('test@smartdairy.com', index: 0);
    },
  );
}
```

---

## 5. Test Organization

### 5.1 Folder Structure

```
test/
├── unit/
│   ├── data/
│   │   ├── models/
│   │   ├── repositories/
│   │   └── datasources/
│   ├── domain/
│   │   ├── usecases/
│   │   └── entities/
│   └── presentation/
│       └── bloc/
├── widget/
│   ├── components/
│   ├── pages/
│   └── golden/
├── integration/
│   ├── pages/
│   ├── flows/
│   └── utils/
├── helpers/
│   ├── test_helpers.dart
│   ├── test_helpers.mocks.dart
│   └── fixture_reader.dart
└── fixtures/
    ├── milk_production.json
    ├── auth_response.json
    └── cattle_list.json
```

### 5.2 Naming Conventions

| Test Type | File Naming | Example |
|-----------|-------------|---------|
| Unit Test | `{subject}_test.dart` | `get_milk_production_test.dart` |
| Widget Test | `{widget}_test.dart` | `milk_production_card_test.dart` |
| Integration | `{feature}_test.dart` | `milk_entry_flow_test.dart` |
| Golden | `{widget}_golden_test.dart` | `login_page_golden_test.dart` |
| BLoC | `{bloc}_bloc_test.dart` | `auth_bloc_test.dart` |

### 5.3 Group Naming

```dart
// Consistent group hierarchy
group('GetMilkProductionUseCase', () {           // Class/Subject
group('call', () {                               // Method
group('when online', () {                        // Condition/State
test('returns data on success', () async { });   // Test case

group('Validation', () {
group('Email Validation', () {
test('accepts valid email format', () { });
test('rejects invalid email format', () { });
```

---

## 6. Test Data

### 6.1 Mock Data (Fixtures)

```dart
// test/fixtures/fixture_reader.dart
import 'dart:io';

String fixture(String name) {
  return File('test/fixtures/$name').readAsStringSync();
}
```

### 6.2 JSON Fixtures

```json
// test/fixtures/milk_production.json
{
  "success": true,
  "data": {
    "production": [
      {
        "date": "2026-01-15",
        "morning_yield": 125.5,
        "evening_yield": 118.3,
        "total_yield": 243.8,
        "cow_count": 45
      },
      {
        "date": "2026-01-16",
        "morning_yield": 126.0,
        "evening_yield": 119.1,
        "total_yield": 245.1,
        "cow_count": 45
      }
    ],
    "summary": {
      "total_monthly": 7562.3,
      "average_daily": 243.9,
      "change_percent": 2.5
    }
  }
}
```

```json
// test/fixtures/auth_response.json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "refresh_token": "dGhpcyBpcyBhIHJlZnJlc2ggdG9rZW4...",
  "user": {
    "id": "user_123",
    "email": "farmer@smartdairy.com",
    "name": "John Farmer",
    "farm_id": "farm_456",
    "role": "admin"
  },
  "expires_in": 3600
}
```

```json
// test/fixtures/error_response.json
{
  "success": false,
  "error": {
    "code": "INVALID_CREDENTIALS",
    "message": "The provided credentials are invalid",
    "details": {
      "field": "password"
    }
  }
}
```

### 6.3 Test Data Builders

```dart
// test/helpers/test_data_builder.dart
class MilkProductionBuilder {
  DateTime _date = DateTime(2026, 1, 15);
  double _morningYield = 125.5;
  double _eveningYield = 118.3;
  int _cowCount = 45;

  MilkProductionBuilder withDate(DateTime date) {
    _date = date;
    return this;
  }

  MilkProductionBuilder withMorningYield(double yield) {
    _morningYield = yield;
    return this;
  }

  MilkProductionBuilder withEveningYield(double yield) {
    _eveningYield = yield;
    return this;
  }

  MilkProductionBuilder withCowCount(int count) {
    _cowCount = count;
    return this;
  }

  MilkProduction build() {
    return MilkProduction(
      date: _date,
      morningYield: _morningYield,
      eveningYield: _eveningYield,
      totalYield: _morningYield + _eveningYield,
      cowCount: _cowCount,
    );
  }
}

// Usage in tests
final highProduction = MilkProductionBuilder()
    .withMorningYield(200.0)
    .withEveningYield(195.0)
    .withCowCount(60)
    .build();
```

### 6.4 Factory Methods

```dart
// test/helpers/factories.dart
MilkProduction createMilkProduction({
  DateTime? date,
  double? morningYield,
  double? eveningYield,
}) {
  return MilkProduction(
    date: date ?? DateTime(2026, 1, 15),
    morningYield: morningYield ?? 125.5,
    eveningYield: eveningYield ?? 118.3,
    totalYield: (morningYield ?? 125.5) + (eveningYield ?? 118.3),
  );
}

List<MilkProduction> createProductionList(int count) {
  return List.generate(
    count,
    (i) => createMilkProduction(
      date: DateTime(2026, 1, 1).add(Duration(days: i)),
      morningYield: 120.0 + i,
      eveningYield: 115.0 + i,
    ),
  );
}
```

---

## 7. Test Environment

### 7.1 Flavor Configuration

```dart
// lib/config/flavor_config.dart
enum Flavor { dev, staging, prod }

class FlavorConfig {
  final Flavor flavor;
  final String apiBaseUrl;
  final bool enableLogging;
  final bool enableAnalytics;

  FlavorConfig._({
    required this.flavor,
    required this.apiBaseUrl,
    required this.enableLogging,
    required this.enableAnalytics,
  });

  static late FlavorConfig _instance;

  static void initialize(Flavor flavor) {
    switch (flavor) {
      case Flavor.dev:
        _instance = FlavorConfig._(
          flavor: flavor,
          apiBaseUrl: 'http://localhost:8080/api',
          enableLogging: true,
          enableAnalytics: false,
        );
        break;
      case Flavor.staging:
        _instance = FlavorConfig._(
          flavor: flavor,
          apiBaseUrl: 'https://staging-api.smartdairy.com/api',
          enableLogging: true,
          enableAnalytics: true,
        );
        break;
      case Flavor.prod:
        _instance = FlavorConfig._(
          flavor: flavor,
          apiBaseUrl: 'https://api.smartdairy.com/api',
          enableLogging: false,
          enableAnalytics: true,
        );
        break;
    }
  }

  static FlavorConfig get instance => _instance;
}
```

### 7.2 Test Environment Setup

```dart
// test/helpers/test_config.dart
import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

void setupTestEnvironment() {
  TestWidgetsFlutterBinding.ensureInitialized();
  
  // Disable network images in tests
  HttpOverrides.global = _TestHttpOverrides();
}

class _TestHttpOverrides extends HttpOverrides {
  @override
  HttpClient createHttpClient(SecurityContext? context) {
    return super.createHttpClient(context)
      ..badCertificateCallback = (cert, host, port) => true;
  }
}
```

### 7.3 Test Initialization

```dart
// test/test_config.dart
import 'helpers/test_config.dart';

void main() {
  setUpAll(() {
    setupTestEnvironment();
  });
}
```

---

## 8. CI Integration

### 8.1 GitHub Actions Workflow

```yaml
# .github/workflows/test.yml
name: Tests

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main, develop]

jobs:
  unit-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true

      - name: Get dependencies
        run: flutter pub get

      - name: Generate mocks
        run: flutter pub run build_runner build --delete-conflicting-outputs

      - name: Run unit tests
        run: flutter test --coverage test/unit/

      - name: Upload coverage
        uses: codecov/codecov-action@v3
        with:
          files: coverage/lcov.info
          fail_ci_if_error: true

  widget-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'

      - name: Get dependencies
        run: flutter pub get

      - name: Generate mocks
        run: flutter pub run build_runner build --delete-conflicting-outputs

      - name: Run widget tests
        run: flutter test test/widget/

  golden-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'

      - name: Get dependencies
        run: flutter pub get

      - name: Run golden tests
        run: flutter test test/widget/golden/

  integration-tests-android:
    runs-on: macos-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'

      - name: Setup Android SDK
        uses: android-actions/setup-android@v3

      - name: Start emulator
        uses: reactivecircus/android-emulator-runner@v2
        with:
          api-level: 29
          script: |
            flutter pub get
            flutter test integration_test/

  integration-tests-ios:
    runs-on: macos-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'

      - name: Start iOS Simulator
        run: |
          xcrun simctl create test-device com.apple.CoreSimulator.SimDeviceType.iPhone-14
          xcrun simctl boot test-device

      - name: Run integration tests
        run: |
          flutter pub get
          flutter test integration_test/
```

### 8.2 Codemagic Configuration

```yaml
# codemagic.yaml
workflows:
  test-workflow:
    name: Test Workflow
    instance_type: mac_mini_m1
    max_build_duration: 60
    environment:
      flutter: stable
    scripts:
      - name: Get dependencies
        script: flutter packages pub get

      - name: Generate mocks
        script: flutter pub run build_runner build --delete-conflicting-outputs

      - name: Unit tests
        script: |
          flutter test --coverage test/unit/
          mkdir -p test-results
          lcov --summary coverage/lcov.info > test-results/coverage-summary.txt

      - name: Widget tests
        script: flutter test test/widget/

      - name: Integration tests (iOS)
        script: |
          flutter build ios --simulator
          flutter test integration_test/ --flavor staging
    artifacts:
      - test-results/**
      - coverage/**
    publishing:
      email:
        recipients:
          - qa@smartdairy.com
```

### 8.3 Local Test Scripts

```bash
#!/bin/bash
# scripts/run_tests.sh

set -e

echo "🧪 Running Smart Dairy Test Suite"

# Generate mocks
echo "📦 Generating mocks..."
flutter pub run build_runner build --delete-conflicting-outputs

# Unit tests
echo "🔬 Running unit tests..."
flutter test test/unit/ --coverage

# Widget tests
echo "🖼️  Running widget tests..."
flutter test test/widget/

# Golden tests
echo "🎨 Running golden tests..."
flutter test test/widget/golden/

# Generate coverage report
echo "📊 Generating coverage report..."
genhtml coverage/lcov.info -o coverage/html

echo "✅ All tests passed!"
echo "📈 Coverage report: coverage/html/index.html"
```

---

## 9. Coverage Requirements

### 9.1 Coverage Targets

| Layer | Minimum Coverage | Target Coverage |
|-------|-----------------|-----------------|
| Domain (Entities, Use Cases) | 90% | 95% |
| Data (Repositories, Models) | 85% | 90% |
| Presentation (BLoCs) | 80% | 85% |
| UI (Widgets) | 70% | 80% |
| **Overall** | **80%** | **85%** |

### 9.2 Coverage Configuration

```yaml
# .codecov.yml
coverage:
  status:
    project:
      default:
        target: 80%
        threshold: 2%
    patch:
      default:
        target: 80%
        threshold: 2%

  ignore:
    - "lib/main.dart"
    - "lib/generated/**"
    - "lib/**/generated_plugin_registrant.dart"
    - "lib/config/**"
    - "lib/core/constants/**"
    - "lib/core/theme/**"
    - "lib/core/utils/logger.dart"
    - "lib/presentation/pages/**/widgets/*.dart"
```

### 9.3 Coverage Exclusions

```yaml
# analysis_options.yaml
analyzer:
  exclude:
    - "**/*.g.dart"
    - "**/*.freezed.dart"
    - "**/generated/**"
    - "test/**"
```

### 9.4 Coverage Commands

```bash
# Run tests with coverage
flutter test --coverage

# View coverage summary
grep -v 'lib/main.dart' coverage/lcov.info | lcov --summary -

# Generate HTML report
genhtml coverage/lcov.info -o coverage/html

# Open report (macOS)
open coverage/html/index.html

# Open report (Linux)
xdg-open coverage/html/index.html
```

---

## 10. Device Testing

### 10.1 Physical Device Matrix

| Device | OS Version | Priority | Owner |
|--------|------------|----------|-------|
| iPhone 15 Pro | iOS 17.x | P1 | QA Team |
| iPhone 14 | iOS 17.x | P1 | QA Team |
| iPhone SE (3rd Gen) | iOS 17.x | P2 | QA Team |
| Samsung Galaxy S24 | Android 14 | P1 | QA Team |
| Google Pixel 8 | Android 14 | P1 | QA Team |
| Xiaomi Redmi Note 13 | Android 13 | P2 | QA Team |
| Samsung Galaxy Tab S9 | Android 14 | P2 | QA Team |
| iPad Air | iPadOS 17.x | P2 | QA Team |

### 10.2 Cloud Testing Services

#### Firebase Test Lab Configuration

```yaml
# .firebaserc
{
  "projects": {
    "default": "smart-dairy-dev"
  }
}
```

```bash
# Run tests on Firebase Test Lab
gcloud firebase test android run \
  --type instrumentation \
  --app build/app/outputs/apk/debug/app-debug.apk \
  --test build/app/outputs/apk/androidTest/debug/app-debug-androidTest.apk \
  --device model=redfin,version=30,locale=en,orientation=portrait \
  --device model=panther,version=33,locale=en,orientation=portrait

# iOS testing
gcloud firebase test ios run \
  --type xctest \
  --test build/ios_integ/Build/Products/Release-iphonesimulator/*.zip \
  --device model=iphone14pro,version=16.6
```

#### AWS Device Farm

```bash
# Upload and run tests on AWS Device Farm
aws devicefarm create-upload \
  --project-arn $PROJECT_ARN \
  --name app-debug.apk \
  --type ANDROID_APP

aws devicefarm schedule-run \
  --project-arn $PROJECT_ARN \
  --app-arn $APP_ARN \
  --device-pool-arn $DEVICE_POOL_ARN \
  --test type=INSTRUMENTATION,testPackageArn=$TEST_ARN
```

### 10.3 Device Testing Checklist

- [ ] App installs without errors
- [ ] App launches within 3 seconds
- [ ] Splash screen displays correctly
- [ ] Login works with valid credentials
- [ ] Biometric authentication works (if available)
- [ ] All navigation flows work correctly
- [ ] Data entry forms validate correctly
- [ ] Camera integration works
- [ ] Push notifications are received
- [ ] Offline mode functions correctly
- [ ] App handles interruptions (calls, notifications)
- [ ] App handles orientation changes
- [ ] App works with different text sizes
- [ ] App works with dark mode
- [ ] Battery usage is acceptable
- [ ] Memory usage is acceptable

---

## 11. Manual Testing Checklist

### 11.1 Exploratory Testing Areas

| Area | Focus Points | Time Budget |
|------|--------------|-------------|
| Onboarding | First-time user experience, tutorials | 30 min |
| Navigation | Menu flows, deep linking, back button | 30 min |
| Data Entry | Forms, validation, keyboard handling | 45 min |
| Reports | Charts, exports, filters | 30 min |
| Sync | Conflict resolution, queue management | 30 min |
| Edge Cases | Empty states, errors, large data | 30 min |

### 11.2 Regression Test Suite

```markdown
## Authentication
- [ ] Login with valid credentials
- [ ] Login with invalid credentials shows error
- [ ] Password reset flow works
- [ ] Biometric login works
- [ ] Session timeout handled correctly
- [ ] Token refresh works

## Milk Production
- [ ] Add morning production entry
- [ ] Add evening production entry
- [ ] Edit existing entry
- [ ] Delete entry with confirmation
- [ ] View daily summary
- [ ] View monthly report
- [ ] Filter by date range
- [ ] Export data to CSV

## Cattle Management
- [ ] Add new cattle record
- [ ] Edit cattle details
- [ ] View cattle health history
- [ ] Search/filter cattle list
- [ ] Upload cattle photo

## Settings
- [ ] Change language
- [ ] Update notification preferences
- [ ] Change password
- [ ] View app version
- [ ] Contact support

## Offline Functionality
- [ ] Create entry while offline
- [ ] View cached data
- [ ] Sync indicator shows correctly
- [ ] Auto-sync on reconnection
- [ ] Conflict resolution UI
```

### 11.3 Bug Report Template

```markdown
## Bug Report

**Title:** [Brief description]

**Environment:**
- App Version: [e.g., 1.2.0]
- Device: [e.g., iPhone 15 Pro]
- OS Version: [e.g., iOS 17.2]
- Environment: [Dev/Staging/Prod]

**Steps to Reproduce:**
1. 
2. 
3. 

**Expected Result:**

**Actual Result:**

**Screenshots/Video:**

**Logs:**
```

---

## 12. Performance Testing

### 12.1 Performance Metrics

| Metric | Target | Critical Threshold |
|--------|--------|-------------------|
| App Launch Time | < 3 seconds | > 5 seconds |
| Screen Load Time | < 1 second | > 2 seconds |
| API Response Time | < 500ms | > 2 seconds |
| Frame Rate | 60 FPS | < 30 FPS |
| Memory Usage | < 150MB | > 250MB |
| APK Size | < 50MB | > 100MB |
| Battery Drain | < 5%/hour | > 10%/hour |

### 12.2 Performance Testing Implementation

```dart
// test/performance/performance_test.dart
import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

void main() {
  group('Performance Tests', () {
    testWidgets('home page renders within budget', (tester) async {
      await tester.pumpWidget(const SmartDairyApp());
      
      // Measure frame build times
      final Stopwatch stopwatch = Stopwatch()..start();
      await tester.pumpAndSettle();
      stopwatch.stop();
      
      expect(stopwatch.elapsedMilliseconds, lessThan(1000));
    });

    testWidgets('scroll performance test', (tester) async {
      await tester.pumpWidget(const ProductionListPage());
      await tester.pumpAndSettle();
      
      // Scroll through list
      final listFinder = find.byType(ListView);
      
      await tester.fling(listFinder, const Offset(0, -500), 1000);
      await tester.pumpAndSettle();
      
      // Performance timeline is automatically collected
    });
  });
}
```

### 12.3 Integration with DevTools

```bash
# Run with performance profiling
flutter run --profile

# Generate performance timeline
flutter drive --target=test_driver/perf.dart --profile

# Memory profiling
flutter run --profile --trace-systrace
```

---

## 13. Accessibility Testing

### 13.1 Accessibility Requirements

| Guideline | Requirement | Test Method |
|-----------|-------------|-------------|
| WCAG 2.1 AA | Minimum compliance | Automated + Manual |
| Screen Reader | Full navigation support | VoiceOver/TalkBack |
| Color Contrast | 4.5:1 minimum ratio | Automated scanner |
| Touch Targets | 48x48 dp minimum | Visual inspection |
| Font Scaling | Support up to 200% | Device settings |

### 13.2 Semantic Labels

```dart
// ✅ GOOD: Proper semantics
IconButton(
  icon: const Icon(Icons.add),
  tooltip: 'Add milk production entry',
  onPressed: _addEntry,
)

TextField(
  decoration: const InputDecoration(
    labelText: 'Milk quantity in liters',
    hintText: 'Enter quantity, e.g., 125.5',
  ),
  keyboardType: TextInputType.number,
)

// For complex widgets
MergeSemantics(
  child: ListTile(
    leading: const Icon(Icons.water_drop),
    title: const Text('Morning Collection'),
    subtitle: Text('${production.morningYield} L'),
  ),
)

// Hide decorative elements
Image.asset(
  'assets/decoration.png',
  excludeFromSemantics: true,
)
```

### 13.3 Accessibility Tests

```dart
// test/widget/accessibility_test.dart
group('Accessibility Tests', () {
  testWidgets('login page has proper labels', (tester) async {
    await tester.pumpWidget(const MaterialApp(home: LoginPage()));
    
    // Verify semantic labels
    final emailField = tester.semantics.find(
      label: 'Email address',
    );
    expect(emailField, isNotNull);
    
    // Check for heading semantics
    final headings = tester.semantics.findAll(
      flags: SemanticsFlag.isHeader,
    );
    expect(headings, isNotEmpty);
  });

  testWidgets('all interactive elements have labels', (tester) async {
    await tester.pumpWidget(const MaterialApp(home: HomePage()));
    
    final semantics = tester.semantics;
    
    // Find all buttons without labels
    final unlabeledButtons = semantics.findAll(
      (node) => node.hasAction(SemanticsAction.tap) && 
                node.label.isEmpty &&
                node.tooltip.isEmpty,
    );
    
    expect(unlabeledButtons, isEmpty);
  });
});
```

### 13.4 Manual Accessibility Checklist

- [ ] All images have alt text
- [ ] Form fields have associated labels
- [ ] Error messages are announced
- [ ] Focus order is logical
- [ ] Modal dialogs trap focus
- [ ] Status updates are announced
- [ ] Color is not sole indicator
- [ ] Text scales correctly (up to 200%)
- [ ] Screen reader navigates all screens
- [ ] Touch targets are minimum 48x48dp

---

## 14. Security Testing

### 14.1 OWASP MASVS Compliance

| MASVS Level | Requirements | Status |
|-------------|--------------|--------|
| L1 | Standard Security | Required |
| L2 | Defense-in-Depth | Required |
| R | Resiliency | Recommended |

### 14.2 Security Test Cases

```dart
// test/security/security_test.dart
group('Security Tests', () {
  test('sensitive data not logged', () {
    final output = captureLogs(() {
      logger.d('User password: $password');
    });
    
    expect(output, isNot(contains(password)));
  });

  test('token storage is encrypted', () async {
    const token = 'sensitive_jwt_token';
    await secureStorage.write(key: 'auth_token', value: token);
    
    // Verify encrypted in storage
    final rawValue = await rawStorage.read(key: 'auth_token');
    expect(rawValue, isNot(equals(token)));
  });

  test('certificate pinning is active', () {
    final dio = createDioClient();
    
    expect(
      dio.httpClientAdapter,
      isA<IOHttpClientAdapter>(),
    );
  });

  test('root detection is enabled', () {
    final jailbreak = JailbreakDetector();
    
    expect(jailbreak.isRooted, completion(anyOf(isTrue, isFalse)));
  });
});
```

### 14.3 Security Testing Tools

```bash
# Static Analysis
flutter analyze --fatal-infos

dart run dart_code_metrics:metrics analyze lib/

# Dependency Check
flutter pub deps

# SAST with MobSF (Mobile Security Framework)
docker run -it -p 8000:8000 opensecurity/mobile-security-framework-mobsf:latest

# Network Security Testing
# Use Burp Suite or OWASP ZAP for MITM testing
```

### 14.4 Security Checklist

- [ ] No hardcoded secrets in code
- [ ] API keys stored in secure storage
- [ ] Certificate pinning implemented
- [ ] Root/jailbreak detection active
- [ ] Obfuscation enabled for release
- [ ] Biometric authentication option available
- [ ] Session timeout implemented
- [ ] Secure clipboard handling
- [ ] Screenshot protection on sensitive screens
- [ ] Data encrypted at rest

---

## 15. Beta Testing

### 15.1 TestFlight Configuration

```
App Store Connect > TestFlight
├── Internal Testing
│   └── Smart Dairy Team (25 members)
├── External Testing
│   ├── Alpha Group (100 users)
│   └── Beta Group (1000 users)
└── Build Distribution
    ├── Auto-notify: Enabled
    └── Test Information: Complete
```

### 15.2 Google Play Console Configuration

```
Google Play Console > Testing
├── Internal Testing
│   └── Smart Dairy Team (100 testers)
├── Closed Testing
│   ├── Alpha Track (200 testers)
│   └── Beta Track (2000 testers)
└── Open Testing
    └── Production Track (Public)
```

### 15.3 Beta Testing Checklist

**Pre-Release:**
- [ ] Release notes prepared
- [ ] Known issues documented
- [ ] Feedback channels configured
- [ ] Crashlytics enabled
- [ ] Analytics enabled
- [ ] Test groups notified

**During Beta:**
- [ ] Monitor crash reports daily
- [ ] Review user feedback weekly
- [ ] Track adoption metrics
- [ ] Update test builds bi-weekly

**Post-Beta:**
- [ ] Compile feedback summary
- [ ] Prioritize bug fixes
- [ ] Update documentation
- [ ] Plan production release

---

## 16. Test Reporting

### 16.1 Test Results Dashboard

```dart
// test_reporter.dart - Custom reporter for CI
import 'package:flutter_test/flutter_test.dart';

class SmartDairyTestReporter extends LiveTestWidgetsFlutterBinding {
  @override
  void handleTestCompletion(
    String testName,
    TestResult result,
    Duration duration,
  ) {
    // Send to monitoring service
    testResults.add({
      'test': testName,
      'result': result.toString(),
      'duration': duration.inMilliseconds,
      'timestamp': DateTime.now().toIso8601String(),
    });
  }
}
```

### 16.2 Test Metrics Tracking

| Metric | Tool | Frequency |
|--------|------|-----------|
| Test Pass Rate | CI Dashboard | Per Build |
| Code Coverage | Codecov | Per PR |
| Flaky Tests | Internal Tracker | Weekly |
| Test Duration | CI Logs | Per Build |
| Bug Escape Rate | JIRA | Monthly |

### 16.3 Test Report Template

```markdown
# Test Report - Sprint 12

**Period:** Jan 15 - Jan 28, 2026
**Build:** v1.2.0-beta.3

## Summary
- Total Tests: 342
- Passed: 338 (98.8%)
- Failed: 4 (1.2%)
- Coverage: 84.2% (+2.1%)

## Unit Tests
- Pass Rate: 99.2%
- Duration: 45s
- New Tests: 12

## Widget Tests
- Pass Rate: 98.5%
- Duration: 2m 15s
- Golden Changes: 0

## Integration Tests
- Pass Rate: 95.0%
- Duration: 8m 30s
- Issues: 2 flaky tests identified

## Action Items
1. Fix flaky integration test for offline sync
2. Add coverage for cattle repository
3. Update golden files for new UI changes
```

---

## 17. Appendices

### Appendix A: Complete Unit Test Example

```dart
// test/data/repositories/milk_production_repository_impl_test.dart
import 'package:dartz/dartz.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:mockito/mockito.dart';
import 'package:smart_dairy/core/errors/failures.dart';
import 'package:smart_dairy/data/models/milk_production_model.dart';
import 'package:smart_dairy/data/repositories/milk_production_repository_impl.dart';

import '../../helpers/test_helpers.mocks.dart';

void main() {
  late MilkProductionRepositoryImpl repository;
  late MockMilkProductionRemoteDataSource mockRemoteDataSource;
  late MockMilkProductionLocalDataSource mockLocalDataSource;
  late MockNetworkInfo mockNetworkInfo;

  setUp(() {
    mockRemoteDataSource = MockMilkProductionRemoteDataSource();
    mockLocalDataSource = MockMilkProductionLocalDataSource();
    mockNetworkInfo = MockNetworkInfo();
    repository = MilkProductionRepositoryImpl(
      remoteDataSource: mockRemoteDataSource,
      localDataSource: mockLocalDataSource,
      networkInfo: mockNetworkInfo,
    );
  });

  group('getDailyProduction', () {
    final tDate = DateTime(2026, 1, 15);
    final tMilkProductionModel = MilkProductionModel(
      date: tDate,
      morningYield: 125.5,
      eveningYield: 118.3,
      cowCount: 45,
    );
    final tMilkProduction = tMilkProductionModel.toEntity();

    test('should check if device is online', () async {
      // arrange
      when(mockNetworkInfo.isConnected).thenAnswer((_) async => true);
      when(mockRemoteDataSource.getDailyProduction(any))
          .thenAnswer((_) async => tMilkProductionModel);
      when(mockLocalDataSource.cacheDailyProduction(any))
          .thenAnswer((_) async => Future.value());

      // act
      await repository.getDailyProduction(tDate);

      // assert
      verify(mockNetworkInfo.isConnected);
    });

    group('device is online', () {
      setUp(() {
        when(mockNetworkInfo.isConnected).thenAnswer((_) async => true);
      });

      test(
        'should return remote data when the call to remote data source is successful',
        () async {
          // arrange
          when(mockRemoteDataSource.getDailyProduction(any))
              .thenAnswer((_) async => tMilkProductionModel);
          when(mockLocalDataSource.cacheDailyProduction(any))
              .thenAnswer((_) async => Future.value());

          // act
          final result = await repository.getDailyProduction(tDate);

          // assert
          verify(mockRemoteDataSource.getDailyProduction(tDate));
          expect(result, equals(Right(tMilkProduction)));
        },
      );

      test(
        'should cache the data locally when the call to remote data source is successful',
        () async {
          // arrange
          when(mockRemoteDataSource.getDailyProduction(any))
              .thenAnswer((_) async => tMilkProductionModel);
          when(mockLocalDataSource.cacheDailyProduction(any))
              .thenAnswer((_) async => Future.value());

          // act
          await repository.getDailyProduction(tDate);

          // assert
          verify(mockLocalDataSource.cacheDailyProduction(tMilkProductionModel));
        },
      );

      test(
        'should return server failure when the call to remote data source is unsuccessful',
        () async {
          // arrange
          when(mockRemoteDataSource.getDailyProduction(any))
              .thenThrow(ServerException());

          // act
          final result = await repository.getDailyProduction(tDate);

          // assert
          verify(mockRemoteDataSource.getDailyProduction(tDate));
          verifyZeroInteractions(mockLocalDataSource);
          expect(result, equals(Left(ServerFailure())));
        },
      );
    });

    group('device is offline', () {
      setUp(() {
        when(mockNetworkInfo.isConnected).thenAnswer((_) async => false);
      });

      test(
        'should return last locally cached data when the cached data is present',
        () async {
          // arrange
          when(mockLocalDataSource.getLastDailyProduction())
              .thenAnswer((_) async => tMilkProductionModel);

          // act
          final result = await repository.getDailyProduction(tDate);

          // assert
          verifyZeroInteractions(mockRemoteDataSource);
          verify(mockLocalDataSource.getLastDailyProduction());
          expect(result, equals(Right(tMilkProduction)));
        },
      );

      test(
        'should return CacheFailure when there is no cached data present',
        () async {
          // arrange
          when(mockLocalDataSource.getLastDailyProduction())
              .thenThrow(CacheException());

          // act
          final result = await repository.getDailyProduction(tDate);

          // assert
          verifyZeroInteractions(mockRemoteDataSource);
          verify(mockLocalDataSource.getLastDailyProduction());
          expect(result, equals(Left(CacheFailure())));
        },
      );
    });
  });
}
```

### Appendix B: Complete Widget Test Example

```dart
// test/presentation/pages/milk_production_page_test.dart
import 'package:bloc_test/bloc_test.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:mocktail/mocktail.dart';
import 'package:smart_dairy/domain/entities/milk_production.dart';
import 'package:smart_dairy/presentation/bloc/milk_production/milk_production_bloc.dart';
import 'package:smart_dairy/presentation/pages/milk_production_page.dart';
import 'package:smart_dairy/presentation/widgets/milk_production_card.dart';

class MockMilkProductionBloc
    extends MockBloc<MilkProductionEvent, MilkProductionState>
    implements MilkProductionBloc {}

void main() {
  late MockMilkProductionBloc mockBloc;

  setUp(() {
    mockBloc = MockMilkProductionBloc();
  });

  Widget createWidgetUnderTest() {
    return MaterialApp(
      home: BlocProvider<MilkProductionBloc>.value(
        value: mockBloc,
        child: const MilkProductionPage(),
      ),
    );
  }

  group('MilkProductionPage', () {
    testWidgets('shows loading indicator when state is initial', (tester) async {
      when(() => mockBloc.state).thenReturn(MilkProductionInitial());

      await tester.pumpWidget(createWidgetUnderTest());

      expect(find.byType(CircularProgressIndicator), findsOneWidget);
    });

    testWidgets('shows loading indicator when state is loading', (tester) async {
      when(() => mockBloc.state).thenReturn(MilkProductionLoading());

      await tester.pumpWidget(createWidgetUnderTest());

      expect(find.byType(CircularProgressIndicator), findsOneWidget);
    });

    testWidgets('shows production list when state is loaded', (tester) async {
      final productionList = [
        MilkProduction(
          date: DateTime(2026, 1, 15),
          morningYield: 125.5,
          eveningYield: 118.3,
        ),
        MilkProduction(
          date: DateTime(2026, 1, 16),
          morningYield: 126.0,
          eveningYield: 119.1,
        ),
      ];

      when(() => mockBloc.state)
          .thenReturn(MilkProductionLoaded(production: productionList));

      await tester.pumpWidget(createWidgetUnderTest());

      expect(find.byType(ListView), findsOneWidget);
      expect(find.byType(MilkProductionCard), findsNWidgets(2));
    });

    testWidgets('shows empty message when state is empty', (tester) async {
      when(() => mockBloc.state).thenReturn(MilkProductionEmpty());

      await tester.pumpWidget(createWidgetUnderTest());

      expect(find.text('No production data available'), findsOneWidget);
      expect(find.byIcon(Icons.inbox), findsOneWidget);
    });

    testWidgets('shows error message when state is error', (tester) async {
      const errorMessage = 'Failed to load data';
      when(() => mockBloc.state)
          .thenReturn(const MilkProductionError(message: errorMessage));

      await tester.pumpWidget(createWidgetUnderTest());

      expect(find.text(errorMessage), findsOneWidget);
      expect(find.byIcon(Icons.error_outline), findsOneWidget);
    });

    testWidgets('refreshes data on pull-to-refresh', (tester) async {
      final productionList = [
        MilkProduction(
          date: DateTime(2026, 1, 15),
          morningYield: 125.5,
          eveningYield: 118.3,
        ),
      ];

      when(() => mockBloc.state)
          .thenReturn(MilkProductionLoaded(production: productionList));
      whenListen(
        mockBloc,
        Stream.fromIterable([
          MilkProductionLoading(),
          MilkProductionLoaded(production: productionList),
        ]),
      );

      await tester.pumpWidget(createWidgetUnderTest());

      await tester.fling(
        find.byType(ListView),
        const Offset(0, 300),
        1000,
      );
      await tester.pumpAndSettle();

      verify(() => mockBloc.add(const RefreshMilkProduction())).called(1);
    });
  });
}
```

### Appendix C: Complete Integration Test Example

```dart
// integration_test/flows/complete_milk_entry_flow_test.dart
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:smart_dairy/main.dart' as app;

import '../pages/login_page.dart';
import '../pages/home_page.dart';
import '../pages/add_milk_page.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Complete Milk Entry Flow', () {
    testWidgets('user logs in and creates milk production entry', (tester) async {
      // Start the app
      app.main();
      await tester.pumpAndSettle();

      // Login
      final loginPage = LoginPage(tester);
      await loginPage.enterEmail('test@smartdairy.com');
      await loginPage.enterPassword('TestPass123!');
      await loginPage.tapLogin();

      // Verify home page loaded
      final homePage = HomePage(tester);
      await homePage.verifyProductionCardVisible();

      // Navigate to add milk entry
      final addMilkPage = await homePage.navigateToAddMilk();

      // Fill out the form
      await addMilkPage.selectDate(DateTime(2026, 1, 20));
      await addMilkPage.selectSession('Morning');
      await addMilkPage.enterQuantity(135.5);
      await addMilkPage.selectCattleGroup('Group A - High Yielders');
      await addMilkPage.addNotes('Good production today');

      // Save the entry
      await addMilkPage.saveEntry();

      // Verify success
      await addMilkPage.verifySuccessMessage('Entry saved successfully');

      // Navigate back and verify entry appears in list
      await addMilkPage.navigateBack();
      await homePage.verifyEntryInList('135.5 L');
    });

    testWidgets('user edits existing milk entry', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Login
      final loginPage = LoginPage(tester);
      final homePage = await loginPage.loginWithCredentials(
        'test@smartdairy.com',
        'TestPass123!',
      );

      // Find and tap existing entry
      await homePage.tapEntryAtIndex(0);

      // Edit entry
      final editPage = EditMilkPage(tester);
      await editPage.clearQuantity();
      await editPage.enterQuantity(140.0);
      await editPage.saveChanges();

      // Verify update
      await editPage.verifySuccessMessage('Entry updated successfully');
    });

    testWidgets('user deletes milk entry', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Login
      final loginPage = LoginPage(tester);
      final homePage = await loginPage.loginWithCredentials(
        'test@smartdairy.com',
        'TestPass123!',
      );

      // Swipe to delete entry
      await homePage.swipeToDeleteEntryAtIndex(0);

      // Confirm deletion
      await homePage.confirmDeletion();

      // Verify deletion
      await homePage.verifyEntryRemoved();
    });
  });
}
```

### Appendix D: Test Coverage Configuration

```yaml
# coverage_exclusions.yaml
exclude:
  # Entry points
  - "lib/main.dart"
  - "lib/main_*.dart"
  
  # Generated files
  - "**/*.g.dart"
  - "**/*.freezed.dart"
  - "**/*.gr.dart"
  - "lib/generated/**"
  
  # Configuration
  - "lib/config/**"
  - "lib/core/constants/**"
  
  # Theme and styling
  - "lib/core/theme/**"
  - "lib/presentation/styles/**"
  
  # Utilities
  - "lib/core/utils/logger.dart"
  - "lib/core/utils/extensions/**"
  
  # Third party wrappers
  - "lib/core/services/analytics_service.dart"
  - "lib/core/services/crashlytics_service.dart"
  
  # Widgetbook/Storybook
  - "lib/widgetbook/**"
```

### Appendix E: CI Test Workflow Template

```yaml
# .github/workflows/smart-dairy-tests.yml
name: Smart Dairy Test Suite

on:
  push:
    branches: [main, develop, 'release/*']
    paths:
      - 'lib/**'
      - 'test/**'
      - 'pubspec.yaml'
  pull_request:
    branches: [main, develop]

env:
  FLUTTER_VERSION: '3.16.0'
  COVERAGE_THRESHOLD: 80

jobs:
  analyze:
    name: Static Analysis
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: ${{ env.FLUTTER_VERSION }}
          cache: true
      
      - run: flutter pub get
      - run: flutter analyze --fatal-infos
      - run: dart format --set-exit-if-changed lib/ test/
      - run: flutter pub run dart_code_metrics:metrics analyze lib/

  unit-tests:
    name: Unit Tests
    runs-on: ubuntu-latest
    needs: analyze
    steps:
      - uses: actions/checkout@v4
      
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: ${{ env.FLUTTER_VERSION }}
          cache: true
      
      - run: flutter pub get
      - run: flutter pub run build_runner build --delete-conflicting-outputs
      
      - name: Run unit tests with coverage
        run: |
          flutter test --coverage test/unit/
          
          # Check coverage threshold
          COVERAGE=$(lcov --summary coverage/lcov.info | grep "lines" | awk '{print $2}' | tr -d '%')
          if (( $(echo "$COVERAGE < $COVERAGE_THRESHOLD" | bc -l) )); then
            echo "❌ Coverage $COVERAGE% is below threshold $COVERAGE_THRESHOLD%"
            exit 1
          fi
          echo "✅ Coverage: $COVERAGE%"
      
      - uses: codecov/codecov-action@v3
        with:
          files: coverage/lcov.info
          fail_ci_if_error: true

  widget-tests:
    name: Widget Tests
    runs-on: ubuntu-latest
    needs: analyze
    steps:
      - uses: actions/checkout@v4
      
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: ${{ env.FLUTTER_VERSION }}
          cache: true
      
      - run: flutter pub get
      - run: flutter pub run build_runner build --delete-conflicting-outputs
      - run: flutter test test/widget/

  golden-tests:
    name: Golden Tests
    runs-on: ubuntu-latest
    needs: analyze
    steps:
      - uses: actions/checkout@v4
      
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: ${{ env.FLUTTER_VERSION }}
          cache: true
      
      - run: flutter pub get
      
      - name: Run golden tests
        run: flutter test test/widget/golden/
      
      - name: Upload golden failures
        if: failure()
        uses: actions/upload-artifact@v3
        with:
          name: golden-failures
          path: test/widget/golden/failures/

  integration-tests:
    name: Integration Tests
    runs-on: macos-latest
    needs: [unit-tests, widget-tests]
    strategy:
      matrix:
        device:
          - "iPhone 15"
          - "iPad Pro (12.9-inch)"
    steps:
      - uses: actions/checkout@v4
      
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: ${{ env.FLUTTER_VERSION }}
          cache: true
      
      - name: Start simulator
        run: |
          UDID=$(xcrun simctl list devices | grep "${{ matrix.device }}" | grep -oE '[A-F0-9-]{36}' | head -1)
          xcrun simctl boot "$UDID"
          sleep 10
      
      - run: flutter pub get
      - run: flutter pub run build_runner build --delete-conflicting-outputs
      
      - name: Run integration tests
        run: flutter test integration_test/ --flavor staging
      
      - name: Upload test results
        if: always()
        uses: actions/upload-artifact@v3
        with:
          name: integration-test-results-${{ matrix.device }}
          path: integration_test/test-results/

  test-summary:
    name: Test Summary
    runs-on: ubuntu-latest
    needs: [unit-tests, widget-tests, golden-tests, integration-tests]
    if: always()
    steps:
      - name: Generate Summary
        run: |
          echo "## 🧪 Test Results" >> $GITHUB_STEP_SUMMARY
          echo "" >> $GITHUB_STEP_SUMMARY
          echo "| Job | Status |" >> $GITHUB_STEP_SUMMARY
          echo "|-----|--------|" >> $GITHUB_STEP_SUMMARY
          echo "| Static Analysis | ${{ needs.analyze.result }} |" >> $GITHUB_STEP_SUMMARY
          echo "| Unit Tests | ${{ needs.unit-tests.result }} |" >> $GITHUB_STEP_SUMMARY
          echo "| Widget Tests | ${{ needs.widget-tests.result }} |" >> $GITHUB_STEP_SUMMARY
          echo "| Golden Tests | ${{ needs.golden-tests.result }} |" >> $GITHUB_STEP_SUMMARY
          echo "| Integration Tests | ${{ needs.integration-tests.result }} |" >> $GITHUB_STEP_SUMMARY
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | QA Engineer | | January 31, 2026 |
| Reviewer | Mobile Lead | | |
| Approver | QA Lead | | |

---

**Document Control:**
- Location: `docs/Implementation_document_list/MOBILE DEVELOPMENT/`
- Classification: Internal
- Retention: Project Duration + 5 Years

**Related Documents:**
- H-001: Mobile App Architecture
- H-002: Mobile Development Guidelines
- H-011: Mobile Security Guidelines

---

*End of Document H-013: Mobile Testing Guidelines*
