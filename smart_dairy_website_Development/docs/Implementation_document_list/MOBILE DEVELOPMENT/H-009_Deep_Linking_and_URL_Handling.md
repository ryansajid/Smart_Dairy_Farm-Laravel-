# Document H-009: Deep Linking & URL Handling

## Smart Dairy Ltd. - Smart Web Portal System

---

| **Metadata** | |
|-------------|---|
| **Document ID** | H-009 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Tech Lead |
| **Status** | Draft |
| **Classification** | Internal Use |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [URL Scheme Design](#2-url-scheme-design)
3. [Android App Links](#3-android-app-links)
4. [iOS Universal Links](#4-ios-universal-links)
5. [Flutter Configuration](#5-flutter-configuration)
6. [Route Definitions](#6-route-definitions)
7. [Navigation from Links](#7-navigation-from-links)
8. [Authentication Context](#8-authentication-context)
9. [Push Notification Integration](#9-push-notification-integration)
10. [Share Integration](#10-share-integration)
11. [QR Code Handling](#11-qr-code-handling)
12. [Testing Deep Links](#12-testing-deep-links)
13. [Troubleshooting](#13-troubleshooting)
14. [Security Considerations](#14-security-considerations)
15. [Appendices](#15-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the deep linking and URL handling strategy for the Smart Dairy mobile application. It establishes standards for universal linking, custom URL schemes, and routing mechanisms to enable seamless navigation from external sources into the app.

### 1.2 Scope

This document covers:
- Deep linking architecture and URL schemes
- Android App Links implementation
- iOS Universal Links configuration
- Flutter GoRouter deep link integration
- Route definitions and navigation patterns
- Push notification deep linking
- QR code routing
- Security considerations

### 1.3 Deep Linking Overview

Deep linking allows users to navigate directly to specific content within the Smart Dairy app from:
- Web browsers (Universal Links / App Links)
- Other applications (Custom URL schemes)
- Push notifications
- QR codes
- Social media shares
- Email campaigns

### 1.4 Use Cases

| Use Case | Description | URL Example |
|----------|-------------|-------------|
| Product Browsing | Direct access to product catalog | `https://smartdairybd.com/products` |
| Product Details | View specific product information | `https://smartdairybd.com/product/123` |
| Order Tracking | Check order status and details | `smartdairy://order/456` |
| Animal Management | Access farm animal records | `https://app.smartdairybd.com/farm/animal/789` |
| Profile Access | Navigate to user profile | `smartdairy://profile` |
| Cart Access | Direct access to shopping cart | `smartdairy://cart` |
| Notifications | View notification center | `smartdairy://notifications` |
| Promotional Campaigns | Marketing deep links | `https://smartdairybd.com/campaign/summer-sale` |

---

## 2. URL Scheme Design

### 2.1 URL Structure

```
[scheme]://[host]/[path]/[path_params]?[query_params]
```

### 2.2 Supported Schemes

| Scheme | Platform | Use Case |
|--------|----------|----------|
| `https://smartdairybd.com` | Web + Mobile | Universal/App Links (Production) |
| `https://app.smartdairybd.com` | Web + Mobile | App-specific deep links |
| `https://staging.smartdairybd.com` | Web + Mobile | Staging environment |
| `smartdairy://` | Mobile Only | Custom URL scheme fallback |

### 2.3 URL Pattern Mapping

| Screen | HTTPS URL | Custom Scheme |
|--------|-----------|---------------|
| Home | `https://smartdairybd.com` | `smartdairy://home` |
| Products List | `https://smartdairybd.com/products` | `smartdairy://products` |
| Product Detail | `https://smartdairybd.com/product/:id` | `smartdairy://product/:id` |
| Orders List | `https://app.smartdairybd.com/orders` | `smartdairy://orders` |
| Order Detail | `https://app.smartdairybd.com/order/:id` | `smartdairy://order/:id` |
| Profile | `https://app.smartdairybd.com/profile` | `smartdairy://profile` |
| Settings | `https://app.smartdairybd.com/settings` | `smartdairy://settings` |
| Farm Animals | `https://app.smartdairybd.com/farm/animals` | `smartdairy://farm/animals` |
| Animal Detail | `https://app.smartdairybd.com/farm/animal/:id` | `smartdairy://farm/animal/:id` |
| Notifications | `https://app.smartdairybd.com/notifications` | `smartdairy://notifications` |
| Cart | `https://app.smartdairybd.com/cart` | `smartdairy://cart` |
| Checkout | `https://app.smartdairybd.com/checkout` | `smartdairy://checkout` |

### 2.4 Query Parameters

| Parameter | Description | Example |
|-----------|-------------|---------|
| `ref` | Referral source | `ref=email_campaign` |
| `utm_source` | UTM tracking | `utm_source=facebook` |
| `utm_medium` | UTM medium | `utm_medium=social` |
| `utm_campaign` | UTM campaign | `utm_campaign=summer2026` |
| `promo_code` | Promotional code | `promo_code=DAIRY20` |
| `return_to` | Return URL after action | `return_to=/cart` |

---

## 3. Android App Links

### 3.1 Domain Verification

App Links require domain verification through Digital Asset Links.

#### Digital Asset Links JSON

Host the following at: `https://smartdairybd.com/.well-known/assetlinks.json`

```json
[{
  "relation": ["delegate_permission/common.handle_all_urls"],
  "target": {
    "namespace": "android_app",
    "package_name": "com.smartdairy.app",
    "sha256_cert_fingerprints": [
      "XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX",
      "YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY:YY"
    ]
  }
},{
  "relation": ["delegate_permission/common.handle_all_urls"],
  "target": {
    "namespace": "android_app",
    "package_name": "com.smartdairy.app",
    "sha256_cert_fingerprints": [
      "ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ:ZZ"
    ]
  }
}]
```

**Note:** Replace fingerprint placeholders with actual SHA256 certificate fingerprints.

### 3.2 Intent Filters Configuration

#### Main Activity Intent Filters

```xml
<!-- android/app/src/main/AndroidManifest.xml -->
<manifest xmlns:android="http://schemas.android.com/apk/res/android"
    package="com.smartdairy.app">

    <application
        android:name="${applicationName}"
        android:icon="@mipmap/ic_launcher"
        android:label="Smart Dairy">
        
        <activity
            android:name=".MainActivity"
            android:exported="true"
            android:launchMode="singleTask"
            android:theme="@style/LaunchTheme"
            android:configChanges="orientation|keyboardHidden|keyboard|screenSize|smallestScreenSize|locale|layoutDirection|fontScale|screenLayout|density|uiMode"
            android:hardwareAccelerated="true"
            android:windowSoftInputMode="adjustResize">
            
            <meta-data
                android:name="io.flutter.embedding.android.NormalTheme"
                android:resource="@style/NormalTheme" />
            
            <intent-filter>
                <action android:name="android.intent.action.MAIN" />
                <category android:name="android.intent.category.LAUNCHER" />
            </intent-filter>

            <!-- Custom URL Scheme -->
            <intent-filter>
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="smartdairy" />
            </intent-filter>

            <!-- App Links - Production Domain -->
            <intent-filter android:autoVerify="true">
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="https" />
                <data android:host="smartdairybd.com" />
                <data android:pathPrefix="/" />
            </intent-filter>

            <!-- App Links - App Subdomain -->
            <intent-filter android:autoVerify="true">
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="https" />
                <data android:host="app.smartdairybd.com" />
                <data android:pathPrefix="/" />
            </intent-filter>

            <!-- App Links - Staging Domain -->
            <intent-filter android:autoVerify="true">
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="https" />
                <data android:host="staging.smartdairybd.com" />
                <data android:pathPrefix="/" />
            </intent-filter>

        </activity>

        <!-- Required for Flutter plugins -->
        <meta-data
            android:name="flutterEmbedding"
            android:value="2" />
            
    </application>
</manifest>
```

### 3.3 Kotlin Native Implementation

```kotlin
// android/app/src/main/kotlin/com/smartdairy/app/MainActivity.kt
package com.smartdairy.app

import android.content.Intent
import android.os.Bundle
import android.util.Log
import io.flutter.embedding.android.FlutterActivity
import io.flutter.embedding.engine.FlutterEngine
import io.flutter.plugin.common.MethodChannel

class MainActivity : FlutterActivity() {
    private val CHANNEL = "com.smartdairy.app/deeplink"
    private var initialLink: String? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        handleIntent(intent)
    }

    override fun onNewIntent(intent: Intent) {
        super.onNewIntent(intent)
        handleIntent(intent)
    }

    private fun handleIntent(intent: Intent?) {
        if (intent?.action == Intent.ACTION_VIEW) {
            val data = intent.data
            data?.let {
                initialLink = it.toString()
                Log.d("DeepLink", "Received: $initialLink")
            }
        }
    }

    override fun configureFlutterEngine(flutterEngine: FlutterEngine) {
        super.configureFlutterEngine(flutterEngine)
        
        MethodChannel(
            flutterEngine.dartExecutor.binaryMessenger,
            CHANNEL
        ).setMethodCallHandler { call, result ->
            when (call.method) {
                "getInitialLink" -> {
                    result.success(initialLink)
                    initialLink = null
                }
                else -> result.notImplemented()
            }
        }
    }
}
```

---

## 4. iOS Universal Links

### 4.1 Apple App Site Association

Host the following at: `https://smartdairybd.com/.well-known/apple-app-site-association`

**Important:** Must be served with `Content-Type: application/json` and no file extension.

```json
{
  "applinks": {
    "details": [
      {
        "appIDs": [
          "TEAM_ID.com.smartdairy.app",
          "TEAM_ID.com.smartdairy.app.staging"
        ],
        "components": [
          {
            "/": "/*",
            "comment": "Match all paths"
          },
          {
            "/": "/product/*",
            "comment": "Product detail pages"
          },
          {
            "/": "/order/*",
            "exclude": true,
            "comment": "Orders handled by app subdomain"
          }
        ]
      },
      {
        "appID": "TEAM_ID.com.smartdairy.app",
        "paths": [
          "/products/*",
          "/product/*",
          "/",
          "/campaign/*"
        ]
      }
    ]
  },
  "webcredentials": {
    "apps": [
      "TEAM_ID.com.smartdairy.app",
      "TEAM_ID.com.smartdairy.app.staging"
    ]
  },
  "appclips": {
    "apps": [
      "TEAM_ID.com.smartdairy.app.Clip"
    ]
  }
}
```

**App Subdomain Association:**
Host at: `https://app.smartdairybd.com/.well-known/apple-app-site-association`

```json
{
  "applinks": {
    "details": [
      {
        "appIDs": [
          "TEAM_ID.com.smartdairy.app",
          "TEAM_ID.com.smartdairy.app.staging"
        ],
        "components": [
          {
            "/": "/*",
            "comment": "Match all app-specific paths"
          },
          {
            "/": "/orders/*",
            "comment": "Order management"
          },
          {
            "/": "/farm/*",
            "comment": "Farm management features"
          }
        ]
      }
    ]
  }
}
```

### 4.2 Entitlements Configuration

```xml
<!-- ios/Runner/Runner.entitlements -->
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <!-- Associated Domains for Universal Links -->
    <key>com.apple.developer.associated-domains</key>
    <array>
        <string>applinks:smartdairybd.com</string>
        <string>applinks:app.smartdairybd.com</string>
        <string>applinks:staging.smartdairybd.com</string>
        <string>webcredentials:smartdairybd.com</string>
        <string>webcredentials:app.smartdairybd.com</string>
    </array>
</dict>
</plist>
```

### 4.3 Staging Entitlements

```xml
<!-- ios/Runner/RunnerStaging.entitlements -->
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>com.apple.developer.associated-domains</key>
    <array>
        <string>applinks:staging.smartdairybd.com</string>
        <string>webcredentials:staging.smartdairybd.com</string>
    </array>
</dict>
</plist>
```

### 4.4 Swift Native Implementation

```swift
// ios/Runner/AppDelegate.swift
import UIKit
import Flutter

@UIApplicationMain
@objc class AppDelegate: FlutterAppDelegate {
    private var deepLinkChannel: FlutterMethodChannel?
    private var initialLink: String?

    override func application(
        _ application: UIApplication,
        didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?
    ) -> Bool {
        
        let controller = window?.rootViewController as! FlutterViewController
        deepLinkChannel = FlutterMethodChannel(
            name: "com.smartdairy.app/deeplink",
            binaryMessenger: controller.binaryMessenger
        )
        
        deepLinkChannel?.setMethodCallHandler({ [weak self] call, result in
            if call.method == "getInitialLink" {
                result(self?.initialLink)
                self?.initialLink = nil
            } else {
                result(FlutterMethodNotImplemented)
            }
        })
        
        // Handle initial universal link
        if let userActivityDict = launchOptions?[.userActivityDictionary] as? [String: Any],
           let userActivity = userActivityDict["UIApplicationLaunchOptionsUserActivityKey"] as? NSUserActivity,
           let url = userActivity.webpageURL {
            initialLink = url.absoluteString
        }
        
        GeneratedPluginRegistrant.register(with: self)
        return super.application(application, didFinishLaunchingWithOptions: launchOptions)
    }

    // Handle Universal Links
    override func application(
        _ application: UIApplication,
        continue userActivity: NSUserActivity,
        restorationHandler: @escaping ([UIUserActivityRestoring]?) -> Void
    ) -> Bool {
        if userActivity.activityType == NSUserActivityTypeBrowsingWeb,
           let url = userActivity.webpageURL {
            handleDeepLink(url: url)
            return true
        }
        return false
    }

    // Handle Custom URL Schemes
    override func application(
        _ app: UIApplication,
        open url: URL,
        options: [UIApplication.OpenURLOptionsKey: Any] = [:]
    ) -> Bool {
        handleDeepLink(url: url)
        return true
    }

    private func handleDeepLink(url: URL) {
        let link = url.absoluteString
        print("Deep link received: \(link)")
        
        // Send to Flutter via method channel or stream
        deepLinkChannel?.invokeMethod("onDeepLink", arguments: ["url": link])
    }
}
```

### 4.5 Info.plist URL Types

```xml
<!-- ios/Runner/Info.plist -->
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleURLName</key>
        <string>com.smartdairy.app</string>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>smartdairy</string>
        </array>
    </dict>
</array>
```

---

## 5. Flutter Configuration

### 5.1 Dependencies

```yaml
# pubspec.yaml
dependencies:
  flutter:
    sdk: flutter
  
  # Routing
  go_router: ^13.0.0
  
  # Deep Linking
  app_links: ^3.5.0
  uni_links: ^0.5.1
  
  # State Management
  flutter_bloc: ^8.1.3
  
  # Storage
  shared_preferences: ^2.2.2
  
  # Platform Channel
  flutter/services.dart
```

### 5.2 GoRouter Configuration

```dart
// lib/routing/app_router.dart
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../features/auth/bloc/auth_bloc.dart';
import '../features/auth/bloc/auth_state.dart';
import '../features/navigation/scaffold_with_navbar.dart';
import '../features/splash/splash_screen.dart';

// Route imports
import 'routes/product_routes.dart';
import 'routes/order_routes.dart';
import 'routes/farm_routes.dart';
import 'routes/profile_routes.dart';
import 'routes/checkout_routes.dart';

class AppRouter {
  static final _rootNavigatorKey = GlobalKey<NavigatorState>();
  static final _shellNavigatorKey = GlobalKey<NavigatorState>();

  static GoRouter getRouter(AuthBloc authBloc) {
    return GoRouter(
      navigatorKey: _rootNavigatorKey,
      initialLocation: '/splash',
      debugLogDiagnostics: true,
      refreshListenable: GoRouterRefreshStream(authBloc.stream),
      redirect: _handleRedirect,
      routes: [
        // Splash Screen
        GoRoute(
          path: '/splash',
          builder: (context, state) => const SplashScreen(),
        ),

        // Shell Route with Bottom Navigation
        ShellRoute(
          navigatorKey: _shellNavigatorKey,
          builder: (context, state, child) {
            return ScaffoldWithNavbar(child: child);
          },
          routes: [
            // Home
            GoRoute(
              path: '/home',
              builder: (context, state) => const HomeScreen(),
            ),

            // Products
            ...ProductRoutes.routes,

            // Orders
            ...OrderRoutes.routes,

            // Farm
            ...FarmRoutes.routes,

            // Profile & Settings
            ...ProfileRoutes.routes,
          ],
        ),

        // Checkout (outside shell - full screen)
        ...CheckoutRoutes.routes,

        // Notification Center
        GoRoute(
          path: '/notifications',
          builder: (context, state) => const NotificationsScreen(),
        ),

        // Campaign/Promotional Pages
        GoRoute(
          path: '/campaign/:campaignId',
          builder: (context, state) {
            final campaignId = state.pathParameters['campaignId']!;
            return CampaignScreen(campaignId: campaignId);
          },
        ),
      ],
      errorBuilder: (context, state) => ErrorScreen(error: state.error),
    );
  }

  static String? _handleRedirect(BuildContext context, GoRouterState state) {
    final authState = context.read<AuthBloc>().state;
    final location = state.uri.toString();

    // Public routes that don't require authentication
    const publicRoutes = [
      '/splash',
      '/products',
      '/product',
      '/campaign',
    ];

    final isPublicRoute = publicRoutes.any((route) => location.startsWith(route));

    // Not authenticated and not a public route -> redirect to login
    if (authState is! AuthAuthenticated && !isPublicRoute && location != '/login') {
      return '/login?return_to=${Uri.encodeComponent(location)}';
    }

    // Already authenticated and on login -> redirect to home
    if (authState is AuthAuthenticated && location == '/login') {
      return '/home';
    }

    return null;
  }
}

// Helper class to convert Stream to Listenable
class GoRouterRefreshStream extends ChangeNotifier {
  GoRouterRefreshStream(Stream<dynamic> stream) {
    notifyListeners();
    _subscription = stream.asBroadcastStream().listen(
      (dynamic _) => notifyListeners(),
    );
  }

  late final StreamSubscription<dynamic> _subscription;

  @override
  void dispose() {
    _subscription.cancel();
    super.dispose();
  }
}
```

### 5.3 Deep Link Service

```dart
// lib/services/deep_link_service.dart
import 'dart:async';
import 'dart:developer' as developer;

import 'package:app_links/app_links.dart';
import 'package:flutter/services.dart';
import 'package:flutter/foundation.dart';

class DeepLinkService {
  static final DeepLinkService _instance = DeepLinkService._internal();
  factory DeepLinkService() => _instance;
  DeepLinkService._internal();

  final AppLinks _appLinks = AppLinks();
  final StreamController<Uri> _deepLinkStreamController = StreamController<Uri>.broadcast();
  
  Stream<Uri> get onDeepLink => _deepLinkStreamController.stream;
  
  static const MethodChannel _channel = MethodChannel('com.smartdairy.app/deeplink');
  
  Uri? _initialLink;
  Uri? get initialLink => _initialLink;

  Future<void> initialize() async {
    try {
      // Get initial link (app was launched via deep link)
      _initialLink = await _appLinks.getInitialLink();
      
      if (_initialLink != null) {
        developer.log('Initial deep link: $_initialLink', name: 'DeepLinkService');
      }

      // Listen for deep links while app is running
      _appLinks.uriLinkStream.listen(
        (Uri uri) {
          developer.log('Deep link received: $uri', name: 'DeepLinkService');
          _handleDeepLink(uri);
        },
        onError: (err) {
          developer.log('Deep link error: $err', name: 'DeepLinkService', error: err);
        },
      );
    } on PlatformException catch (e) {
      developer.log('Platform exception: $e', name: 'DeepLinkService', error: e);
    } catch (e) {
      developer.log('Error initializing deep links: $e', name: 'DeepLinkService', error: e);
    }
  }

  void _handleDeepLink(Uri uri) {
    // Validate the URI
    if (!_isValidDeepLink(uri)) {
      developer.log('Invalid deep link: $uri', name: 'DeepLinkService');
      return;
    }

    // Add to stream for consumers
    _deepLinkStreamController.add(uri);
  }

  bool _isValidDeepLink(Uri uri) {
    // Whitelist of allowed hosts
    const allowedHosts = [
      'smartdairybd.com',
      'app.smartdairybd.com',
      'staging.smartdairybd.com',
    ];

    // Custom scheme
    if (uri.scheme == 'smartdairy') {
      return true;
    }

    // HTTPS links
    if (uri.scheme == 'https' && allowedHosts.contains(uri.host)) {
      return true;
    }

    return false;
  }

  /// Parse deep link and return route information
  DeepLinkRoute parseDeepLink(Uri uri) {
    final path = uri.path;
    final params = uri.queryParameters;

    // Product routes
    if (path.startsWith('/product/')) {
      final productId = path.split('/').last;
      return DeepLinkRoute(
        route: '/product/$productId',
        arguments: {'productId': productId, ...params},
      );
    }

    if (path == '/products') {
      return DeepLinkRoute(
        route: '/products',
        arguments: params,
      );
    }

    // Order routes
    if (path.startsWith('/order/')) {
      final orderId = path.split('/').last;
      return DeepLinkRoute(
        route: '/order/$orderId',
        arguments: {'orderId': orderId, ...params},
      );
    }

    if (path == '/orders') {
      return DeepLinkRoute(route: '/orders', arguments: params);
    }

    // Farm routes
    if (path.startsWith('/farm/animal/')) {
      final animalId = path.split('/').last;
      return DeepLinkRoute(
        route: '/farm/animal/$animalId',
        arguments: {'animalId': animalId, ...params},
      );
    }

    if (path == '/farm/animals') {
      return DeepLinkRoute(route: '/farm/animals', arguments: params);
    }

    // Profile & Settings
    if (path == '/profile') {
      return DeepLinkRoute(route: '/profile', arguments: params);
    }

    if (path == '/settings') {
      return DeepLinkRoute(route: '/settings', arguments: params);
    }

    // Cart & Checkout
    if (path == '/cart') {
      return DeepLinkRoute(route: '/cart', arguments: params);
    }

    if (path == '/checkout') {
      return DeepLinkRoute(route: '/checkout', arguments: params);
    }

    // Notifications
    if (path == '/notifications') {
      return DeepLinkRoute(route: '/notifications', arguments: params);
    }

    // Campaigns
    if (path.startsWith('/campaign/')) {
      final campaignId = path.split('/').last;
      return DeepLinkRoute(
        route: '/campaign/$campaignId',
        arguments: {'campaignId': campaignId, ...params},
      );
    }

    // Home fallback
    if (path == '/' || path == '/home') {
      return DeepLinkRoute(route: '/home', arguments: params);
    }

    // Unknown route - return home
    return DeepLinkRoute(route: '/home', arguments: params);
  }

  void dispose() {
    _deepLinkStreamController.close();
  }
}

class DeepLinkRoute {
  final String route;
  final Map<String, dynamic> arguments;

  DeepLinkRoute({
    required this.route,
    required this.arguments,
  });

  @override
  String toString() => 'DeepLinkRoute(route: $route, arguments: $arguments)';
}
```



### 5.4 Deep Link Handler Widget

```dart
// lib/widgets/deep_link_handler.dart
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../services/deep_link_service.dart';

class DeepLinkHandler extends StatefulWidget {
  final Widget child;

  const DeepLinkHandler({
    super.key,
    required this.child,
  });

  @override
  State<DeepLinkHandler> createState() => _DeepLinkHandlerState();
}

class _DeepLinkHandlerState extends State<DeepLinkHandler> {
  final DeepLinkService _deepLinkService = DeepLinkService();

  @override
  void initState() {
    super.initState();
    _handleInitialLink();
    _listenToDeepLinks();
  }

  void _handleInitialLink() {
    final initialLink = _deepLinkService.initialLink;
    if (initialLink != null) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        _navigateToDeepLink(initialLink);
      });
    }
  }

  void _listenToDeepLinks() {
    _deepLinkService.onDeepLink.listen((uri) {
      _navigateToDeepLink(uri);
    });
  }

  void _navigateToDeepLink(Uri uri) {
    final deepLinkRoute = _deepLinkService.parseDeepLink(uri);
    
    if (mounted) {
      // Extract query parameters for tracking
      final queryParams = uri.queryParameters;
      _trackDeepLink(uri, queryParams);

      // Navigate to the route
      context.go(
        deepLinkRoute.route,
        extra: deepLinkRoute.arguments,
      );
    }
  }

  void _trackDeepLink(Uri uri, Map<String, String> queryParams) {
    // Track UTM parameters
    final utmSource = queryParams['utm_source'];
    final utmMedium = queryParams['utm_medium'];
    final utmCampaign = queryParams['utm_campaign'];
    final ref = queryParams['ref'];

    // Send analytics event
    AnalyticsService.trackEvent(
      'deep_link_opened',
      parameters: {
        'url': uri.toString(),
        'path': uri.path,
        if (utmSource != null) 'utm_source': utmSource,
        if (utmMedium != null) 'utm_medium': utmMedium,
        if (utmCampaign != null) 'utm_campaign': utmCampaign,
        if (ref != null) 'ref': ref,
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return widget.child;
  }
}
```

---

## 6. Route Definitions

### 6.1 Product Routes

```dart
// lib/routing/routes/product_routes.dart
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../features/products/screens/product_list_screen.dart';
import '../../features/products/screens/product_detail_screen.dart';

class ProductRoutes {
  static List<RouteBase> get routes => [
    GoRoute(
      path: '/products',
      name: 'products',
      builder: (context, state) {
        final categoryId = state.uri.queryParameters['category'];
        final searchQuery = state.uri.queryParameters['search'];
        
        return ProductListScreen(
          categoryId: categoryId,
          searchQuery: searchQuery,
        );
      },
    ),
    GoRoute(
      path: '/product/:productId',
      name: 'product_detail',
      builder: (context, state) {
        final productId = state.pathParameters['productId']!;
        final promoCode = state.uri.queryParameters['promo_code'];
        final refSource = state.uri.queryParameters['ref'];
        
        return ProductDetailScreen(
          productId: productId,
          promoCode: promoCode,
          refSource: refSource,
        );
      },
    ),
  ];
}
```

### 6.2 Order Routes

```dart
// lib/routing/routes/order_routes.dart
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../features/orders/screens/order_list_screen.dart';
import '../../features/orders/screens/order_detail_screen.dart';

class OrderRoutes {
  static List<RouteBase> get routes => [
    GoRoute(
      path: '/orders',
      name: 'orders',
      builder: (context, state) {
        final status = state.uri.queryParameters['status'];
        final fromDate = state.uri.queryParameters['from'];
        final toDate = state.uri.queryParameters['to'];
        
        return OrderListScreen(
          statusFilter: status,
          fromDate: fromDate != null ? DateTime.parse(fromDate) : null,
          toDate: toDate != null ? DateTime.parse(toDate) : null,
        );
      },
    ),
    GoRoute(
      path: '/order/:orderId',
      name: 'order_detail',
      builder: (context, state) {
        final orderId = state.pathParameters['orderId']!;
        final showTracking = state.uri.queryParameters['tracking'] == 'true';
        
        return OrderDetailScreen(
          orderId: orderId,
          showTrackingFirst: showTracking,
        );
      },
    ),
  ];
}
```

### 6.3 Farm Routes

```dart
// lib/routing/routes/farm_routes.dart
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../features/farm/screens/animal_list_screen.dart';
import '../../features/farm/screens/animal_detail_screen.dart';

class FarmRoutes {
  static List<RouteBase> get routes => [
    GoRoute(
      path: '/farm/animals',
      name: 'farm_animals',
      builder: (context, state) {
        final status = state.uri.queryParameters['status'];
        final breed = state.uri.queryParameters['breed'];
        
        return AnimalListScreen(
          statusFilter: status,
          breedFilter: breed,
        );
      },
    ),
    GoRoute(
      path: '/farm/animal/:animalId',
      name: 'animal_detail',
      builder: (context, state) {
        final animalId = state.pathParameters['animalId']!;
        final tab = state.uri.queryParameters['tab'] ?? 'overview';
        
        return AnimalDetailScreen(
          animalId: animalId,
          initialTab: tab,
        );
      },
    ),
  ];
}
```

### 6.4 Profile & Settings Routes

```dart
// lib/routing/routes/profile_routes.dart
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../features/profile/screens/profile_screen.dart';
import '../../features/profile/screens/settings_screen.dart';
import '../../features/profile/screens/edit_profile_screen.dart';

class ProfileRoutes {
  static List<RouteBase> get routes => [
    GoRoute(
      path: '/profile',
      name: 'profile',
      builder: (context, state) => const ProfileScreen(),
    ),
    GoRoute(
      path: '/settings',
      name: 'settings',
      builder: (context, state) {
        final section = state.uri.queryParameters['section'];
        return SettingsScreen(initialSection: section);
      },
    ),
    GoRoute(
      path: '/profile/edit',
      name: 'edit_profile',
      builder: (context, state) => const EditProfileScreen(),
    ),
  ];
}
```

### 6.5 Checkout Routes

```dart
// lib/routing/routes/checkout_routes.dart
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../features/cart/screens/cart_screen.dart';
import '../../features/checkout/screens/checkout_screen.dart';
import '../../features/checkout/screens/payment_screen.dart';
import '../../features/checkout/screens/order_confirmation_screen.dart';

class CheckoutRoutes {
  static List<RouteBase> get routes => [
    GoRoute(
      path: '/cart',
      name: 'cart',
      builder: (context, state) {
        final promoCode = state.uri.queryParameters['promo_code'];
        return CartScreen(appliedPromoCode: promoCode);
      },
    ),
    GoRoute(
      path: '/checkout',
      name: 'checkout',
      builder: (context, state) {
        final params = state.extra as Map<String, dynamic>?;
        return CheckoutScreen(
          preselectedAddressId: params?['addressId'] as String?,
        );
      },
    ),
    GoRoute(
      path: '/checkout/payment',
      name: 'payment',
      builder: (context, state) {
        final orderId = state.uri.queryParameters['order_id']!;
        return PaymentScreen(orderId: orderId);
      },
    ),
    GoRoute(
      path: '/checkout/confirmation',
      name: 'order_confirmation',
      builder: (context, state) {
        final orderId = state.uri.queryParameters['order_id']!;
        return OrderConfirmationScreen(orderId: orderId);
      },
    ),
  ];
}
```

---

## 7. Navigation from Links

### 7.1 Parameter Parsing

```dart
// lib/services/navigation_parser.dart
class NavigationParser {
  /// Parse product ID from various formats
  static String? parseProductId(String? value) {
    if (value == null || value.isEmpty) return null;
    
    // Support both numeric and UUID formats
    final uuidRegex = RegExp(
      r'^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$',
      caseSensitive: false,
    );
    
    if (uuidRegex.hasMatch(value)) return value;
    
    // Numeric ID
    if (int.tryParse(value) != null) return value;
    
    return null;
  }

  /// Parse order ID
  static String? parseOrderId(String? value) {
    if (value == null || value.isEmpty) return null;
    
    // Order IDs typically start with 'ORD' followed by numbers
    final orderRegex = RegExp(r'^ORD-\d{6,}$', caseSensitive: false);
    
    if (orderRegex.hasMatch(value)) return value;
    if (int.tryParse(value) != null) return 'ORD-${value.padLeft(8, '0')}';
    
    return null;
  }

  /// Parse animal ID
  static String? parseAnimalId(String? value) {
    if (value == null || value.isEmpty) return null;
    
    // Animal IDs typically start with 'ANM' or are UUIDs
    final animalRegex = RegExp(r'^ANM-\d+$', caseSensitive: false);
    final uuidRegex = RegExp(
      r'^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$',
      caseSensitive: false,
    );
    
    if (animalRegex.hasMatch(value) || uuidRegex.hasMatch(value)) {
      return value;
    }
    
    return null;
  }

  /// Parse date parameter
  static DateTime? parseDateParam(String? value) {
    if (value == null || value.isEmpty) return null;
    
    try {
      return DateTime.parse(value);
    } catch (e) {
      // Try alternative formats
      final formats = [
        'yyyy-MM-dd',
        'dd/MM/yyyy',
        'MM-dd-yyyy',
      ];
      
      for (final format in formats) {
        try {
          // Use intl package for parsing
          return DateFormat(format).parse(value);
        } catch (_) {
          continue;
        }
      }
    }
    
    return null;
  }

  /// Parse boolean parameter
  static bool parseBoolParam(String? value, {bool defaultValue = false}) {
    if (value == null) return defaultValue;
    
    return value.toLowerCase() == 'true' || 
           value == '1' || 
           value.toLowerCase() == 'yes';
  }

  /// Parse list parameter (comma-separated)
  static List<String> parseListParam(String? value) {
    if (value == null || value.isEmpty) return [];
    
    return value.split(',').map((e) => e.trim()).where((e) => e.isNotEmpty).toList();
  }
}
```

### 7.2 State Restoration

```dart
// lib/services/state_restoration_service.dart
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';

class StateRestorationService {
  static const String _pendingDeepLinkKey = 'pending_deep_link';
  static const String _navigationStackKey = 'navigation_stack';

  /// Save pending deep link when app is terminated
  static Future<void> savePendingDeepLink(String url) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_pendingDeepLinkKey, url);
  }

  /// Get and clear pending deep link
  static Future<String?> getPendingDeepLink() async {
    final prefs = await SharedPreferences.getInstance();
    final link = prefs.getString(_pendingDeepLinkKey);
    if (link != null) {
      await prefs.remove(_pendingDeepLinkKey);
    }
    return link;
  }

  /// Save navigation stack for restoration
  static Future<void> saveNavigationStack(List<String> routes) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_navigationStackKey, jsonEncode(routes));
  }

  /// Restore navigation stack
  static Future<List<String>> getNavigationStack() async {
    final prefs = await SharedPreferences.getInstance();
    final stackJson = prefs.getString(_navigationStackKey);
    
    if (stackJson != null) {
      await prefs.remove(_navigationStackKey);
      try {
        return List<String>.from(jsonDecode(stackJson));
      } catch (_) {
        return [];
      }
    }
    
    return [];
  }

  /// Clear all restoration data
  static Future<void> clearRestorationData() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_pendingDeepLinkKey);
    await prefs.remove(_navigationStackKey);
  }
}
```

---

## 8. Authentication Context

### 8.1 Protected Route Guard

```dart
// lib/routing/guards/auth_guard.dart
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../services/auth_service.dart';
import '../../services/state_restoration_service.dart';

class AuthGuard {
  static final List<String> _publicRoutes = [
    '/',
    '/home',
    '/products',
    '/product',
    '/login',
    '/register',
    '/forgot-password',
    '/campaign',
  ];

  static final List<String> _protectedRoutes = [
    '/orders',
    '/order',
    '/profile',
    '/settings',
    '/farm',
    '/cart',
    '/checkout',
    '/notifications',
  ];

  static bool isPublicRoute(String path) {
    return _publicRoutes.any((route) => path.startsWith(route));
  }

  static bool isProtectedRoute(String path) {
    return _protectedRoutes.any((route) => path.startsWith(route));
  }

  static Future<String?> guard(BuildContext context, GoRouterState state) async {
    final path = state.uri.toString();
    final isAuthenticated = await AuthService.isAuthenticated();

    // Allow public routes
    if (isPublicRoute(path)) {
      return null;
    }

    // Protect sensitive routes
    if (isProtectedRoute(path) && !isAuthenticated) {
      // Save the intended destination for post-login redirect
      await StateRestorationService.savePendingDeepLink(path);
      
      return '/login?return_to=${Uri.encodeComponent(path)}';
    }

    return null;
  }
}
```

### 8.2 Login with Return URL

```dart
// lib/features/auth/screens/login_screen.dart
class LoginScreen extends StatefulWidget {
  final String? returnTo;
  
  const LoginScreen({
    super.key,
    this.returnTo,
  });

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) return;

    try {
      final authService = AuthService();
      await authService.login(
        email: _emailController.text,
        password: _passwordController.text,
      );

      if (mounted) {
        // Navigate to return URL or home
        final returnTo = widget.returnTo;
        if (returnTo != null && returnTo.isNotEmpty) {
          context.go(returnTo);
        } else {
          context.go('/home');
        }
      }
    } catch (e) {
      // Show error
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Login failed: $e')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Login')),
      body: Form(
        key: _formKey,
        child: Column(
          children: [
            TextFormField(
              controller: _emailController,
              decoration: const InputDecoration(labelText: 'Email'),
              validator: (value) => 
                value?.isEmpty ?? true ? 'Please enter email' : null,
            ),
            TextFormField(
              controller: _passwordController,
              decoration: const InputDecoration(labelText: 'Password'),
              obscureText: true,
              validator: (value) =>
                value?.isEmpty ?? true ? 'Please enter password' : null,
            ),
            ElevatedButton(
              onPressed: _login,
              child: const Text('Login'),
            ),
          ],
        ),
      ),
    );
  }
}
```

---

## 9. Push Notification Integration

### 9.1 Notification Payload Format

```json
{
  "to": "device_fcm_token",
  "notification": {
    "title": "Order Shipped!",
    "body": "Your order #ORD-123456 has been shipped and will arrive tomorrow."
  },
  "data": {
    "type": "order_shipped",
    "click_action": "FLUTTER_NOTIFICATION_CLICK",
    "deep_link": "https://app.smartdairybd.com/order/ORD-123456?tracking=true",
    "order_id": "ORD-123456",
    "tracking_number": "TRK789456123",
    "sound": "default",
    "priority": "high"
  },
  "android": {
    "priority": "high",
    "notification": {
      "channel_id": "order_updates",
      "click_action": "FLUTTER_NOTIFICATION_CLICK"
    }
  },
  "apns": {
    "payload": {
      "aps": {
        "alert": {
          "title": "Order Shipped!",
          "body": "Your order #ORD-123456 has been shipped."
        },
        "badge": 1,
        "sound": "default",
        "category": "ORDER_UPDATE"
      }
    }
  }
}
```

### 9.2 Notification Handler Service

```dart
// lib/services/notification_service.dart
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/material.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';

import 'deep_link_service.dart';

class NotificationService {
  static final NotificationService _instance = NotificationService._internal();
  factory NotificationService() => _instance;
  NotificationService._internal();

  final FirebaseMessaging _fcm = FirebaseMessaging.instance;
  final FlutterLocalNotificationsPlugin _localNotifications = 
      FlutterLocalNotificationsPlugin();
  final DeepLinkService _deepLinkService = DeepLinkService();

  Future<void> initialize() async {
    // Request permissions
    await _fcm.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    // Configure local notifications
    const androidSettings = AndroidInitializationSettings('@mipmap/ic_launcher');
    const iosSettings = DarwinInitializationSettings(
      requestAlertPermission: true,
      requestBadgePermission: true,
      requestSoundPermission: true,
    );
    
    await _localNotifications.initialize(
      const InitializationSettings(
        android: androidSettings,
        iOS: iosSettings,
      ),
      onDidReceiveNotificationResponse: _handleNotificationTap,
    );

    // Handle foreground messages
    FirebaseMessaging.onMessage.listen(_handleForegroundMessage);

    // Handle background/terminated messages
    FirebaseMessaging.onMessageOpenedApp.listen(_handleBackgroundMessage);

    // Check for initial message (app opened from terminated state)
    final initialMessage = await _fcm.getInitialMessage();
    if (initialMessage != null) {
      _handleInitialMessage(initialMessage);
    }
  }

  void _handleForegroundMessage(RemoteMessage message) {
    final notification = message.notification;
    final data = message.data;

    if (notification != null) {
      _showLocalNotification(
        id: message.hashCode,
        title: notification.title ?? 'Smart Dairy',
        body: notification.body ?? '',
        payload: data['deep_link'],
      );
    }
  }

  void _handleBackgroundMessage(RemoteMessage message) {
    final deepLink = message.data['deep_link'];
    if (deepLink != null) {
      _navigateToDeepLink(deepLink);
    }
  }

  void _handleInitialMessage(RemoteMessage message) {
    final deepLink = message.data['deep_link'];
    if (deepLink != null) {
      // Store for handling after app initialization
      StateRestorationService.savePendingDeepLink(deepLink);
    }
  }

  void _handleNotificationTap(NotificationResponse response) {
    final payload = response.payload;
    if (payload != null) {
      _navigateToDeepLink(payload);
    }
  }

  void _navigateToDeepLink(String deepLink) {
    final uri = Uri.parse(deepLink);
    _deepLinkService.handleDeepLink(uri);
  }

  Future<void> _showLocalNotification({
    required int id,
    required String title,
    required String body,
    String? payload,
  }) async {
    const androidDetails = AndroidNotificationDetails(
      'smart_dairy_channel',
      'Smart Dairy Notifications',
      channelDescription: 'Main notification channel for Smart Dairy',
      importance: Importance.high,
      priority: Priority.high,
      showWhen: true,
    );

    const iosDetails = DarwinNotificationDetails(
      presentAlert: true,
      presentBadge: true,
      presentSound: true,
    );

    await _localNotifications.show(
      id,
      title,
      body,
      const NotificationDetails(
        android: androidDetails,
        iOS: iosDetails,
      ),
      payload: payload,
    );
  }

  // Subscribe to topics
  Future<void> subscribeToTopic(String topic) async {
    await _fcm.subscribeToTopic(topic);
  }

  Future<void> unsubscribeFromTopic(String topic) async {
    await _fcm.unsubscribeFromTopic(topic);
  }

  // Get FCM token
  Future<String?> getToken() async {
    return await _fcm.getToken();
  }
}
```

---

## 10. Share Integration

### 10.1 Receiving Shared Content

```dart
// lib/services/share_service.dart
import 'package:receive_sharing_intent/receive_sharing_intent.dart';
import 'dart:async';

class ShareService {
  static final ShareService _instance = ShareService._internal();
  factory ShareService() => _instance;
  ShareService._internal();

  StreamSubscription? _intentDataStreamSubscription;

  void initialize({
    required Function(List<SharedMediaFile>) onMediaReceived,
    required Function(String) onTextReceived,
  }) {
    // Listen for media sharing
    _intentDataStreamSubscription =
        ReceiveSharingIntent.instance.getMediaStream().listen(
      (List<SharedMediaFile> value) {
        onMediaReceived(value);
      },
      onError: (err) {
        print("Share Error: $err");
      },
    );

    // Get initial shared media
    ReceiveSharingIntent.instance.getInitialMedia().then(
      (List<SharedMediaFile> value) {
        if (value.isNotEmpty) {
          onMediaReceived(value);
        }
      },
    );

    // Listen for text sharing
    ReceiveSharingIntent.instance.getTextStream().listen(
      (String value) {
        onTextReceived(value);
      },
      onError: (err) {
        print("Share Text Error: $err");
      },
    );

    // Get initial shared text
    ReceiveSharingIntent.instance.getInitialText().then((String? value) {
      if (value != null && value.isNotEmpty) {
        onTextReceived(value);
      }
    });
  }

  void dispose() {
    _intentDataStreamSubscription?.cancel();
  }
}
```

### 10.2 Share Handler Implementation

```dart
// lib/widgets/share_handler.dart
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:receive_sharing_intent/receive_sharing_intent.dart';

import '../services/share_service.dart';
import '../services/deep_link_service.dart';

class ShareHandler extends StatefulWidget {
  final Widget child;

  const ShareHandler({super.key, required this.child});

  @override
  State<ShareHandler> createState() => _ShareHandlerState();
}

class _ShareHandlerState extends State<ShareHandler> {
  final ShareService _shareService = ShareService();

  @override
  void initState() {
    super.initState();
    _shareService.initialize(
      onMediaReceived: _handleSharedMedia,
      onTextReceived: _handleSharedText,
    );
  }

  void _handleSharedMedia(List<SharedMediaFile> files) {
    if (files.isEmpty) return;

    // Handle different media types
    for (final file in files) {
      switch (file.type) {
        case SharedMediaType.image:
          _handleSharedImage(file);
          break;
        case SharedMediaType.video:
          _handleSharedVideo(file);
          break;
        case SharedMediaType.file:
          _handleSharedFile(file);
          break;
        case SharedMediaType.text:
          _handleSharedText(file.path);
          break;
        case SharedMediaType.url:
          _handleSharedUrl(file.path);
          break;
      }
    }
  }

  void _handleSharedImage(SharedMediaFile file) {
    // Navigate to image upload/scan screen
    context.push('/upload/image', extra: {'filePath': file.path});
  }

  void _handleSharedVideo(SharedMediaFile file) {
    // Navigate to video upload screen
    context.push('/upload/video', extra: {'filePath': file.path});
  }

  void _handleSharedFile(SharedMediaFile file) {
    // Show file import dialog
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Import File'),
        content: Text('Import ${file.path.split('/').last}?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context);
              _processSharedFile(file);
            },
            child: const Text('Import'),
          ),
        ],
      ),
    );
  }

  void _processSharedFile(SharedMediaFile file) {
    // Determine file type and navigate accordingly
    final extension = file.path.split('.').last.toLowerCase();
    
    switch (extension) {
      case 'csv':
      case 'xlsx':
        context.push('/import/spreadsheet', extra: {'filePath': file.path});
        break;
      case 'pdf':
        context.push('/import/document', extra: {'filePath': file.path});
        break;
      default:
        context.push('/upload/file', extra: {'filePath': file.path});
    }
  }

  void _handleSharedText(String text) {
    // Check if text is a URL
    if (_isUrl(text)) {
      _handleSharedUrl(text);
      return;
    }

    // Check if text contains Smart Dairy product/order reference
    final productMatch = RegExp(r'SD-PROD-(\d+)').firstMatch(text);
    if (productMatch != null) {
      final productId = productMatch.group(1);
      context.push('/product/$productId');
      return;
    }

    final orderMatch = RegExp(r'SD-ORD-(\d+)').firstMatch(text);
    if (orderMatch != null) {
      final orderId = orderMatch.group(1);
      context.push('/order/$orderId');
      return;
    }

    // Default: show share dialog
    context.push('/share/create', extra: {'text': text});
  }

  void _handleSharedUrl(String url) {
    // Check if it's a Smart Dairy URL
    if (url.contains('smartdairybd.com')) {
      final deepLinkService = DeepLinkService();
      final uri = Uri.parse(url);
      final route = deepLinkService.parseDeepLink(uri);
      context.push(route.route, extra: route.arguments);
    } else {
      // Open in external browser
      // launchUrl(Uri.parse(url), mode: LaunchMode.externalApplication);
    }
  }

  bool _isUrl(String text) {
    return Uri.tryParse(text)?.hasAbsolutePath ?? false;
  }

  @override
  Widget build(BuildContext context) {
    return widget.child;
  }
}
```



---

## 11. QR Code Handling

### 11.1 QR Scanner Integration

```dart
// lib/features/qr/screens/qr_scanner_screen.dart
import 'package:flutter/material.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import 'package:go_router/go_router.dart';

import '../../../services/deep_link_service.dart';

class QRScannerScreen extends StatefulWidget {
  const QRScannerScreen({super.key});

  @override
  State<QRScannerScreen> createState() => _QRScannerScreenState();
}

class _QRScannerScreenState extends State<QRScannerScreen> {
  bool _isProcessing = false;
  final DeepLinkService _deepLinkService = DeepLinkService();

  void _onDetect(BarcodeCapture capture) {
    if (_isProcessing) return;

    final barcode = capture.barcodes.firstOrNull;
    if (barcode == null || barcode.rawValue == null) return;

    setState(() => _isProcessing = true);

    final value = barcode.rawValue!;
    _processQRCode(value);
  }

  void _processQRCode(String value) {
    // Check if it's a Smart Dairy URL
    if (value.contains('smartdairybd.com') || value.startsWith('smartdairy://')) {
      final uri = Uri.parse(value);
      final route = _deepLinkService.parseDeepLink(uri);
      
      Navigator.pop(context);
      context.push(route.route, extra: route.arguments);
      return;
    }

    // Check for product codes
    if (value.startsWith('SDP-')) {
      final productId = value.replaceFirst('SDP-', '');
      Navigator.pop(context);
      context.push('/product/$productId');
      return;
    }

    // Check for order codes
    if (value.startsWith('SDO-')) {
      final orderId = value.replaceFirst('SDO-', 'ORD-');
      Navigator.pop(context);
      context.push('/order/$orderId');
      return;
    }

    // Check for animal tags
    if (value.startsWith('SDA-')) {
      final animalId = value.replaceFirst('SDA-', '');
      Navigator.pop(context);
      context.push('/farm/animal/$animalId');
      return;
    }

    // WiFi configuration
    if (value.startsWith('WIFI:')) {
      _handleWifiQR(value);
      return;
    }

    // Unknown QR code
    _showUnknownQRDialog(value);
  }

  void _handleWifiQR(String value) {
    // Parse WiFi configuration
    // WIFI:T:WPA;S:network_name;P:password;H:false;;
    final ssidMatch = RegExp(r'S:([^;]+)').firstMatch(value);
    final passwordMatch = RegExp(r'P:([^;]+)').firstMatch(value);
    
    final ssid = ssidMatch?.group(1);
    final password = passwordMatch?.group(1);

    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('WiFi Network'),
        content: Text('Connect to "$ssid"?'),
        actions: [
          TextButton(
            onPressed: () {
              setState(() => _isProcessing = false);
              Navigator.pop(context);
            },
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context);
              // Connect to WiFi
              _connectToWifi(ssid!, password);
            },
            child: const Text('Connect'),
          ),
        ],
      ),
    );
  }

  Future<void> _connectToWifi(String ssid, String? password) async {
    // Use platform channel to connect to WiFi
    // Or open WiFi settings
    // await WiFiForIoTPlugin.connect(ssid, password: password);
    
    setState(() => _isProcessing = false);
  }

  void _showUnknownQRDialog(String value) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Unknown QR Code'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('The scanned QR code is not recognized.'),
            const SizedBox(height: 8),
            Text(
              'Content: $value',
              style: const TextStyle(fontSize: 12, color: Colors.grey),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () {
              setState(() => _isProcessing = false);
              Navigator.pop(context);
            },
            child: const Text('Scan Again'),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context);
              // Copy to clipboard
              // Clipboard.setData(ClipboardData(text: value));
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Copied to clipboard')),
              );
              setState(() => _isProcessing = false);
            },
            child: const Text('Copy'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Scan QR Code')),
      body: Stack(
        children: [
          MobileScanner(
            onDetect: _onDetect,
            fit: BoxFit.cover,
          ),
          CustomPaint(
            painter: QRScannerOverlay(),
            child: const SizedBox.expand(),
          ),
          if (_isProcessing)
            Container(
              color: Colors.black54,
              child: const Center(
                child: CircularProgressIndicator(),
              ),
            ),
        ],
      ),
    );
  }
}

class QRScannerOverlay extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = Colors.black54
      ..style = PaintingStyle.fill;

    final scanAreaSize = size.width * 0.7;
    final scanAreaRect = RRect.fromRectAndRadius(
      Rect.fromCenter(
        center: size.center(Offset.zero),
        width: scanAreaSize,
        height: scanAreaSize,
      ),
      const Radius.circular(12),
    );

    final path = Path()
      ..addRect(Rect.fromLTWH(0, 0, size.width, size.height))
      ..addRRect(scanAreaRect);

    canvas.drawPath(path, paint..blendMode = BlendMode.clear);
    canvas.drawPath(path, paint..blendMode = BlendMode.srcOver);

    // Draw corner markers
    final markerPaint = Paint()
      ..color = Colors.green
      ..strokeWidth = 4
      ..style = PaintingStyle.stroke;

    final cornerLength = scanAreaSize * 0.15;
    final center = size.center(Offset.zero);
    final halfSize = scanAreaSize / 2;

    // Top-left corner
    canvas.drawLine(
      Offset(center.dx - halfSize, center.dy - halfSize + cornerLength),
      Offset(center.dx - halfSize, center.dy - halfSize),
      markerPaint,
    );
    canvas.drawLine(
      Offset(center.dx - halfSize, center.dy - halfSize),
      Offset(center.dx - halfSize + cornerLength, center.dy - halfSize),
      markerPaint,
    );

    // Top-right corner
    canvas.drawLine(
      Offset(center.dx + halfSize - cornerLength, center.dy - halfSize),
      Offset(center.dx + halfSize, center.dy - halfSize),
      markerPaint,
    );
    canvas.drawLine(
      Offset(center.dx + halfSize, center.dy - halfSize),
      Offset(center.dx + halfSize, center.dy - halfSize + cornerLength),
      markerPaint,
    );

    // Bottom-left corner
    canvas.drawLine(
      Offset(center.dx - halfSize, center.dy + halfSize - cornerLength),
      Offset(center.dx - halfSize, center.dy + halfSize),
      markerPaint,
    );
    canvas.drawLine(
      Offset(center.dx - halfSize, center.dy + halfSize),
      Offset(center.dx - halfSize + cornerLength, center.dy + halfSize),
      markerPaint,
    );

    // Bottom-right corner
    canvas.drawLine(
      Offset(center.dx + halfSize - cornerLength, center.dy + halfSize),
      Offset(center.dx + halfSize, center.dy + halfSize),
      markerPaint,
    );
    canvas.drawLine(
      Offset(center.dx + halfSize, center.dy + halfSize),
      Offset(center.dx + halfSize, center.dy + halfSize - cornerLength),
      markerPaint,
    );
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
```

### 11.2 QR Code Generation

```dart
// lib/utils/qr_generator.dart
import 'package:qr_flutter/qr_flutter.dart';
import 'package:flutter/material.dart';

class QRGenerator {
  /// Generate product QR code
  static Widget productQR(String productId, {double size = 200}) {
    final qrData = 'https://smartdairybd.com/product/$productId';
    return QrImageView(
      data: qrData,
      version: QrVersions.auto,
      size: size,
      errorCorrectionLevel: QrErrorCorrectLevel.H,
      embeddedImage: const AssetImage('assets/images/logo_small.png'),
      embeddedImageStyle: const QrEmbeddedImageStyle(
        size: Size(40, 40),
      ),
    );
  }

  /// Generate order tracking QR code
  static Widget orderQR(String orderId, {double size = 200}) {
    final qrData = 'SDO-${orderId.replaceFirst('ORD-', '')}';
    return QrImageView(
      data: qrData,
      version: QrVersions.auto,
      size: size,
      errorCorrectionLevel: QrErrorCorrectLevel.M,
    );
  }

  /// Generate animal tag QR code
  static Widget animalQR(String animalId, {double size = 150}) {
    final qrData = 'SDA-$animalId';
    return QrImageView(
      data: qrData,
      version: QrVersions.auto,
      size: size,
      errorCorrectionLevel: QrErrorCorrectLevel.H,
    );
  }

  /// Generate share QR code
  static Widget shareQR(String url, {double size = 200}) {
    return QrImageView(
      data: url,
      version: QrVersions.auto,
      size: size,
      errorCorrectionLevel: QrErrorCorrectLevel.M,
      eyeStyle: const QrEyeStyle(
        eyeShape: QrEyeShape.square,
        color: Color(0xFF2E7D32),
      ),
      dataModuleStyle: const QrDataModuleStyle(
        dataModuleShape: QrDataModuleShape.square,
        color: Color(0xFF2E7D32),
      ),
    );
  }
}
```

---

## 12. Testing Deep Links

### 12.1 ADB Commands (Android)

```bash
# ============================================
# Android Deep Link Testing Commands
# ============================================

# Test custom URL scheme
adb shell am start -W -a android.intent.action.VIEW -d "smartdairy://home" com.smartdairy.app

# Test product detail
adb shell am start -W -a android.intent.action.VIEW -d "smartdairy://product/123" com.smartdairy.app

# Test with query parameters
adb shell am start -W -a android.intent.action.VIEW -d "smartdairy://product/123?promo_code=DAIRY20&ref=email" com.smartdairy.app

# Test order deep link
adb shell am start -W -a android.intent.action.VIEW -d "smartdairy://order/ORD-001234" com.smartdairy.app

# Test HTTPS App Link - Production
adb shell am start -W -a android.intent.action.VIEW -d "https://smartdairybd.com/products" com.smartdairy.app

# Test HTTPS App Link - Product Detail
adb shell am start -W -a android.intent.action.VIEW -d "https://smartdairybd.com/product/456" com.smartdairy.app

# Test farm animal link
adb shell am start -W -a android.intent.action.VIEW -d "https://app.smartdairybd.com/farm/animal/ANM-789" com.smartdairy.app

# Test with UTM parameters
adb shell am start -W -a android.intent.action.VIEW -d "https://smartdairybd.com/campaign/summer-sale?utm_source=test&utm_medium=adb" com.smartdairy.app

# Verify App Link domain verification
adb shell pm verify-app-links --re-verify com.smartdairy.app

# Check domain verification status
adb shell pm get-app-links com.smartdairy.app

# Reset domain verification
adb shell pm set-app-links --package com.smartdairy.app 0

# Enable domain verification
adb shell pm set-app-links --package com.smartdairy.app 1

# Test with specific activity
adb shell am start -n com.smartdairy.app/.MainActivity -a android.intent.action.VIEW -d "smartdairy://cart"

# Simulate notification click
adb shell am start -W -a android.intent.action.VIEW -d "smartdairy://notifications" com.smartdairy.app
```

### 12.2 iOS Testing Commands

```bash
# ============================================
# iOS Deep Link Testing Commands
# ============================================

# Using xcrun simctl

# Open URL in simulator
xcrun simctl openurl booted "smartdairy://home"

# Test product detail
xcrun simctl openurl booted "smartdairy://product/123"

# Test Universal Link - Production
xcrun simctl openurl booted "https://smartdairybd.com/products"

# Test Universal Link - Product
xcrun simctl openurl booted "https://smartdairybd.com/product/456"

# Test App Subdomain
xcrun simctl openurl booted "https://app.smartdairybd.com/orders"

# Test farm animal
xcrun simctl openurl booted "https://app.smartdairybd.com/farm/animal/789"

# Using ios-deploy for physical device

# Install and launch with deep link
ios-deploy --bundle build/ios/iphoneos/Runner.app --args "--uri=smartdairy://profile"

# Using Xcode Command Line

# Launch app with URL
xcodebuild test -workspace Runner.xcworkspace -scheme Runner -destination 'platform=iOS Simulator,name=iPhone 15' -testPlan DeepLinkTests
```

### 12.3 iOS Universal Link Testing

```bash
# ============================================
# iOS Universal Link Validation
# ============================================

# Check apple-app-site-association file
curl -v https://smartdairybd.com/.well-known/apple-app-site-association

# Verify JSON is valid
curl -s https://smartdairybd.com/.well-known/apple-app-site-association | python -m json.tool

# Test with swcutil (macOS)
swcutil diagnose -u https://smartdairybd.com/product/123

# Check associated domains registration
# Device Console Filter: swcd OR installcoordinationd

# Reset Universal Link preferences on device
# Settings > General > Reset > Reset Location & Privacy

# Verify associated domains entitlement
codesign -d --entitlements - /path/to/Runner.app

# Check app receipt
# Device: Settings > Developer > Universal Links

# Console app filters
# subsystem:com.apple.swcd OR process:swcd
```

### 12.4 Flutter Integration Tests

```dart
// test/deep_link_test.dart
import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:smart_dairy/main.dart' as app;

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Deep Link Tests', () {
    testWidgets('Navigate to product via deep link', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Simulate deep link
      const deepLink = 'smartdairy://product/123';
      
      // Trigger deep link
      await tester.tap(find.byKey(const Key('test_deep_link_button')));
      await tester.pumpAndSettle();

      // Verify navigation
      expect(find.text('Product Details'), findsOneWidget);
      expect(find.text('Product ID: 123'), findsOneWidget);
    });

    testWidgets('Navigate to order with tracking', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      const deepLink = 'smartdairy://order/ORD-123456?tracking=true';
      
      // Navigate via deep link
      await tester.tap(find.byKey(const Key('test_deep_link_button')));
      await tester.pumpAndSettle();

      // Verify order screen
      expect(find.text('Order #ORD-123456'), findsOneWidget);
      expect(find.text('Tracking'), findsOneWidget);
    });

    testWidgets('Navigate to farm animal', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      const deepLink = 'smartdairy://farm/animal/ANM-789';
      
      await tester.tap(find.byKey(const Key('test_deep_link_button')));
      await tester.pumpAndSettle();

      expect(find.text('Animal Details'), findsOneWidget);
      expect(find.text('ANM-789'), findsOneWidget);
    });

    testWidgets('Protected route redirects to login', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Log out first
      await tester.tap(find.byIcon(Icons.logout));
      await tester.pumpAndSettle();

      // Try to access protected route
      const deepLink = 'smartdairy://orders';
      
      await tester.tap(find.byKey(const Key('test_deep_link_button')));
      await tester.pumpAndSettle();

      // Should redirect to login
      expect(find.text('Login'), findsOneWidget);
    });
  });
}
```

### 12.5 Automated Testing Script

```bash
#!/bin/bash
# test_deep_links.sh

echo "=========================================="
echo "Smart Dairy Deep Link Testing Script"
echo "=========================================="

APP_PACKAGE="com.smartdairy.app"
TEST_URLS=(
  "smartdairy://home"
  "smartdairy://products"
  "smartdairy://product/123"
  "smartdairy://product/456?promo_code=TEST20"
  "smartdairy://orders"
  "smartdairy://order/ORD-001234"
  "smartdairy://profile"
  "smartdairy://settings"
  "smartdairy://farm/animals"
  "smartdairy://farm/animal/ANM-789"
  "smartdairy://cart"
  "smartdairy://notifications"
  "https://smartdairybd.com/products"
  "https://smartdairybd.com/product/789"
  "https://app.smartdairybd.com/orders"
)

echo "Testing ${#TEST_URLS[@]} deep links..."
echo ""

for url in "${TEST_URLS[@]}"; do
  echo "Testing: $url"
  
  if [[ $url == https://* ]]; then
    # App Link
    adb shell am start -W -a android.intent.action.VIEW -d "$url" $APP_PACKAGE 2>&1 | grep -E "(Status|Time|Error)" || echo "Launched"
  else
    # Custom scheme
    adb shell am start -W -a android.intent.action.VIEW -d "$url" $APP_PACKAGE 2>&1 | grep -E "(Status|Time|Error)" || echo "Launched"
  fi
  
  sleep 2
  echo "---"
done

echo ""
echo "Testing complete!"
```

---

## 13. Troubleshooting

### 13.1 Common Issues

| Issue | Cause | Solution |
|-------|-------|----------|
| App doesn't open from deep link | Intent filter not configured | Verify AndroidManifest.xml intent filters |
| Universal Link opens in browser | Domain verification failed | Check Digital Asset Links / AASA file |
| Custom scheme not working | Scheme not registered | Verify Info.plist (iOS) or manifest (Android) |
| Parameters not passed | URL encoding issue | Use Uri.encodeComponent() for values |
| Deep link delayed | App not fully initialized | Handle in addPostFrameCallback |
| iOS Universal Link fails | AASA file not accessible | Ensure file at /.well-known/apple-app-site-association |
| Android App Link shows chooser | autoVerify="false" | Set android:autoVerify="true" |
| Route not found | GoRouter path mismatch | Check route definitions match URL patterns |
| Auth redirect loop | return_to parameter issue | Validate and sanitize return URLs |

### 13.2 Debug Tips

#### Android Debugging

```bash
# Enable debug logging for Intent resolution
adb shell setprop log.tag.IntentResolver DEBUG

# Monitor logcat for deep link activity
adb logcat -s "ActivityManager:*" "IntentResolver:*" | grep -i "smartdairy\|View"

# Check package verification status
adb shell dumpsys package com.smartdairy.app | grep -A 20 "Domain Verification"

# Verify intent filter resolution
adb shell am resolve-activity -a android.intent.action.VIEW -d "smartdairy://home"

# Monitor domain verification
adb shell cmd domain_verification print-statistics

# Reset app link preferences
adb shell pm clear-app-links-user-selection com.smartdairy.app
```

#### iOS Debugging

```bash
# Check Universal Link registration
# Device Console Filter: swcd OR installcoordinationd

# Reset Universal Link preferences on device
# Settings > General > Reset > Reset Location & Privacy

# Verify associated domains entitlement
codesign -d --entitlements - /path/to/Runner.app

# Check app receipt
# Device: Settings > Developer > Universal Links

# Console app filters
# subsystem:com.apple.swcd OR process:swcd
```

#### Flutter Debugging

```dart
// Enable GoRouter debug logging
GoRouter(
  debugLogDiagnostics: true,
  // ...
);

// Add deep link logging
void _handleDeepLink(Uri uri) {
  debugPrint('Deep Link Received:');
  debugPrint('  Scheme: ${uri.scheme}');
  debugPrint('  Host: ${uri.host}');
  debugPrint('  Path: ${uri.path}');
  debugPrint('  Query: ${uri.queryParameters}');
  
  // ... handle link
}

// Validate deep link in tests
test('parse product deep link', () {
  final service = DeepLinkService();
  final uri = Uri.parse('smartdairy://product/123?promo=TEST');
  
  final route = service.parseDeepLink(uri);
  
  expect(route.route, '/product/123');
  expect(route.arguments['promo'], 'TEST');
});
```

### 13.3 Diagnostic Checklist

- [ ] Android intent filters configured correctly
- [ ] iOS Associated Domains entitlement added
- [ ] AASA file hosted at /.well-known/apple-app-site-association
- [ ] Digital Asset Links file hosted at /.well-known/assetlinks.json
- [ ] Correct SHA256 fingerprint in assetlinks.json
- [ ] Correct Team ID and Bundle ID in AASA file
- [ ] No file extension on AASA file (iOS)
- [ ] Proper Content-Type headers on hosted files
- [ ] GoRouter routes match URL patterns
- [ ] Query parameter parsing implemented
- [ ] Authentication guards in place
- [ ] Error handling for invalid links
- [ ] Analytics tracking implemented



---

## 14. Security Considerations

### 14.1 URL Validation

```dart
// lib/utils/security/url_validator.dart
class URLValidator {
  static final List<String> _allowedSchemes = [
    'https',
    'smartdairy',
  ];

  static final List<String> _allowedHosts = [
    'smartdairybd.com',
    'app.smartdairybd.com',
    'staging.smartdairybd.com',
  ];

  static final List<String> _blockedPaths = [
    '/admin',
    '/api/internal',
    '/config',
    '/.env',
    '/../',
  ];

  /// Validate deep link URL
  static bool isValidDeepLink(String url) {
    try {
      final uri = Uri.parse(url);

      // Validate scheme
      if (!_allowedSchemes.contains(uri.scheme)) {
        return false;
      }

      // HTTPS links must have allowed host
      if (uri.scheme == 'https' && !_allowedHosts.contains(uri.host)) {
        return false;
      }

      // Check for blocked paths
      for (final blocked in _blockedPaths) {
        if (uri.path.contains(blocked)) {
          return false;
        }
      }

      // Prevent path traversal
      if (uri.path.contains('..')) {
        return false;
      }

      // Validate query parameters
      if (!_areQueryParamsValid(uri.queryParameters)) {
        return false;
      }

      return true;
    } catch (e) {
      return false;
    }
  }

  static bool _areQueryParamsValid(Map<String, String> params) {
    for (final entry in params.entries) {
      // Check for SQL injection patterns
      if (_containsSQLInjection(entry.value)) {
        return false;
      }

      // Check for XSS patterns
      if (_containsXSS(entry.value)) {
        return false;
      }

      // Check for command injection
      if (_containsCommandInjection(entry.value)) {
        return false;
      }
    }
    return true;
  }

  static bool _containsSQLInjection(String value) {
    final sqlPatterns = [
      RegExp(r'(\%27)|(\')|(\-\-)|(\%23)|(#)', caseSensitive: false),
      RegExp(r'((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))', caseSensitive: false),
      RegExp(r'\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))', caseSensitive: false),
      RegExp(r'((\%27)|(\'))union', caseSensitive: false),
      RegExp(r'exec(\s|\+)+(s|x)p\w+', caseSensitive: false),
    ];

    return sqlPatterns.any((pattern) => pattern.hasMatch(value));
  }

  static bool _containsXSS(String value) {
    final xssPatterns = [
      RegExp(r'<script[^>]*>[\s\S]*?</script>', caseSensitive: false),
      RegExp(r'javascript:', caseSensitive: false),
      RegExp(r'on\w+\s*=', caseSensitive: false),
      RegExp(r'<iframe', caseSensitive: false),
      RegExp(r'<object', caseSensitive: false),
      RegExp(r'<embed', caseSensitive: false),
    ];

    return xssPatterns.any((pattern) => pattern.hasMatch(value));
  }

  static bool _containsCommandInjection(String value) {
    final cmdPatterns = [
      RegExp(r'[;&|`]'),
      RegExp(r'\$\('),
      RegExp(r'`.*`'),
    ];

    return cmdPatterns.any((pattern) => pattern.hasMatch(value));
  }

  /// Sanitize URL parameter
  static String sanitizeParam(String value) {
    return value
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#x27;')
        .trim();
  }

  /// Validate return URL to prevent open redirect
  static bool isValidReturnUrl(String returnUrl) {
    try {
      final uri = Uri.parse(returnUrl);

      // Only allow relative URLs or same-domain URLs
      if (uri.scheme.isEmpty) {
        // Relative URL - valid
        return !returnUrl.startsWith('//');
      }

      // Absolute URL - must be allowed domain
      return _allowedHosts.contains(uri.host);
    } catch (e) {
      return false;
    }
  }
}
```

### 14.2 Secure Deep Link Handler

```dart
// lib/services/secure_deep_link_handler.dart
import 'dart:developer' as developer;

import '../utils/security/url_validator.dart';

class SecureDeepLinkHandler {
  /// Process deep link with security validations
  static Future<DeepLinkResult> processDeepLink(String url) async {
    // Validate URL
    if (!URLValidator.isValidDeepLink(url)) {
      developer.log(
        'Blocked potentially malicious deep link: $url',
        name: 'Security',
      );
      return DeepLinkResult.blocked('Invalid or unsafe URL');
    }

    try {
      final uri = Uri.parse(url);

      // Rate limiting check
      if (!await _checkRateLimit(uri)) {
        return DeepLinkResult.blocked('Rate limit exceeded');
      }

      // Validate authentication for sensitive routes
      if (_isSensitiveRoute(uri) && !await _isAuthenticated()) {
        return DeepLinkResult.requiresAuth(uri);
      }

      // Log valid deep link for audit
      _logDeepLinkAccess(uri);

      return DeepLinkResult.success(uri);
    } catch (e) {
      developer.log(
        'Error processing deep link: $e',
        name: 'Security',
        error: e,
      );
      return DeepLinkResult.error('Processing failed');
    }
  }

  static bool _isSensitiveRoute(Uri uri) {
    final sensitivePaths = [
      '/orders',
      '/order/',
      '/profile',
      '/settings',
      '/farm/',
      '/checkout',
      '/cart',
    ];

    return sensitivePaths.any((path) => uri.path.startsWith(path));
  }

  static Future<bool> _isAuthenticated() async {
    // Check auth state
    return await AuthService.isAuthenticated();
  }

  static Future<bool> _checkRateLimit(Uri uri) async {
    // Implement rate limiting (e.g., max 10 deep links per minute)
    // Use local storage or in-memory cache
    return true;
  }

  static void _logDeepLinkAccess(Uri uri) {
    developer.log(
      'Deep link accessed: ${uri.toString()}',
      name: 'Audit',
    );
    // Send to analytics/audit service
  }
}

class DeepLinkResult {
  final DeepLinkStatus status;
  final Uri? uri;
  final String? message;

  DeepLinkResult._(this.status, this.uri, this.message);

  factory DeepLinkResult.success(Uri uri) =>
      DeepLinkResult._(DeepLinkStatus.success, uri, null);

  factory DeepLinkResult.blocked(String reason) =>
      DeepLinkResult._(DeepLinkStatus.blocked, null, reason);

  factory DeepLinkResult.requiresAuth(Uri uri) =>
      DeepLinkResult._(DeepLinkStatus.requiresAuth, uri, null);

  factory DeepLinkResult.error(String message) =>
      DeepLinkResult._(DeepLinkStatus.error, null, message);

  bool get isSuccess => status == DeepLinkStatus.success;
  bool get isBlocked => status == DeepLinkStatus.blocked;
  bool get requiresAuth => status == DeepLinkStatus.requiresAuth;
}

enum DeepLinkStatus {
  success,
  blocked,
  requiresAuth,
  error,
}
```

### 14.3 Certificate Pinning

```dart
// lib/utils/security/certificate_pinning.dart
import 'dart:io';
import 'package:http/io_client.dart';

class CertificatePinning {
  static HttpClient createSecureClient() {
    final context = SecurityContext.defaultContext;

    // Add trusted certificates
    // context.setTrustedCertificatesBytes(certData);

    final httpClient = HttpClient(context: context);

    httpClient.badCertificateCallback = (X509Certificate cert, String host, int port) {
      // Only allow specific hosts
      final allowedHosts = [
        'smartdairybd.com',
        'app.smartdairybd.com',
        'api.smartdairybd.com',
      ];

      if (!allowedHosts.contains(host)) {
        return false;
      }

      // Implement certificate pinning
      // Verify certificate fingerprint matches expected value
      // final certFingerprint = sha256.convert(cert.der).toString();
      // return expectedFingerprints.contains(certFingerprint);

      return true;
    };

    return httpClient;
  }
}
```

---

## 15. Appendices

### Appendix A: Complete AndroidManifest.xml

```xml
<?xml version="1.0" encoding="utf-8"?>
<manifest xmlns:android="http://schemas.android.com/apk/res/android"
    package="com.smartdairy.app">

    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.QUERY_ALL_PACKAGES" />

    <queries>
        <intent>
            <action android:name="android.intent.action.VIEW" />
            <data android:scheme="https" />
        </intent>
        <intent>
            <action android:name="android.intent.action.SEND" />
            <data android:mimeType="*/*" />
        </intent>
    </queries>

    <application
        android:name="${applicationName}"
        android:icon="@mipmap/ic_launcher"
        android:label="Smart Dairy"
        android:usesCleartextTraffic="false">

        <activity
            android:name=".MainActivity"
            android:exported="true"
            android:launchMode="singleTask"
            android:theme="@style/LaunchTheme"
            android:configChanges="orientation|keyboardHidden|keyboard|screenSize|smallestScreenSize|locale|layoutDirection|fontScale|screenLayout|density|uiMode"
            android:hardwareAccelerated="true"
            android:windowSoftInputMode="adjustResize">

            <meta-data
                android:name="io.flutter.embedding.android.NormalTheme"
                android:resource="@style/NormalTheme" />

            <intent-filter>
                <action android:name="android.intent.action.MAIN" />
                <category android:name="android.intent.category.LAUNCHER" />
            </intent-filter>

            <!-- Custom URL Scheme -->
            <intent-filter>
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="smartdairy" />
            </intent-filter>

            <!-- App Links - Production -->
            <intent-filter android:autoVerify="true">
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="https" />
                <data android:host="smartdairybd.com" />
                <data android:pathPrefix="/" />
            </intent-filter>

            <!-- App Links - App Subdomain -->
            <intent-filter android:autoVerify="true">
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="https" />
                <data android:host="app.smartdairybd.com" />
                <data android:pathPrefix="/" />
            </intent-filter>

            <!-- App Links - Staging -->
            <intent-filter android:autoVerify="true">
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="https" />
                <data android:host="staging.smartdairybd.com" />
                <data android:pathPrefix="/" />
            </intent-filter>
        </activity>

        <meta-data
            android:name="flutterEmbedding"
            android:value="2" />

        <!-- Firebase Cloud Messaging -->
        <service
            android:name=".MyFirebaseMessagingService"
            android:exported="false">
            <intent-filter>
                <action android:name="com.google.firebase.MESSAGING_EVENT" />
            </intent-filter>
        </service>

        <!-- Default notification channel -->
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_channel_id"
            android:value="smart_dairy_channel" />

    </application>
</manifest>
```

### Appendix B: Complete Info.plist

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>CFBundleDevelopmentRegion</key>
    <string>$(DEVELOPMENT_LANGUAGE)</string>
    <key>CFBundleDisplayName</key>
    <string>Smart Dairy</string>
    <key>CFBundleExecutable</key>
    <string>$(EXECUTABLE_NAME)</string>
    <key>CFBundleIdentifier</key>
    <string>$(PRODUCT_BUNDLE_IDENTIFIER)</string>
    <key>CFBundleInfoDictionaryVersion</key>
    <string>6.0</string>
    <key>CFBundleName</key>
    <string>smart_dairy</string>
    <key>CFBundlePackageType</key>
    <string>APPL</string>
    <key>CFBundleShortVersionString</key>
    <string>$(FLUTTER_BUILD_NAME)</string>
    <key>CFBundleSignature</key>
    <string>????</string>
    <key>CFBundleVersion</key>
    <string>$(FLUTTER_BUILD_NUMBER)</string>
    <key>LSRequiresIPhoneOS</key>
    <true/>
    <key>UILaunchStoryboardName</key>
    <string>LaunchScreen</string>
    <key>UIMainStoryboardFile</key>
    <string>Main</string>
    <key>UISupportedInterfaceOrientations</key>
    <array>
        <string>UIInterfaceOrientationPortrait</string>
        <string>UIInterfaceOrientationLandscapeLeft</string>
        <string>UIInterfaceOrientationLandscapeRight</string>
    </array>
    <key>UISupportedInterfaceOrientations~ipad</key>
    <array>
        <string>UIInterfaceOrientationPortrait</string>
        <string>UIInterfaceOrientationPortraitUpsideDown</string>
        <string>UIInterfaceOrientationLandscapeLeft</string>
        <string>UIInterfaceOrientationLandscapeRight</string>
    </array>
    <key>UIViewControllerBasedStatusBarAppearance</key>
    <false/>

    <!-- URL Types for Custom Scheme -->
    <key>CFBundleURLTypes</key>
    <array>
        <dict>
            <key>CFBundleURLName</key>
            <string>com.smartdairy.app</string>
            <key>CFBundleURLSchemes</key>
            <array>
                <string>smartdairy</string>
            </array>
        </dict>
    </array>

    <!-- Flutter-specific -->
    <key>UIApplicationSupportsIndirectInputEvents</key>
    <true/>

    <!-- Required for camera/QR scanning -->
    <key>NSCameraUsageDescription</key>
    <string>This app needs camera access to scan QR codes</string>

    <!-- Required for photo library -->
    <key>NSPhotoLibraryUsageDescription</key>
    <string>This app needs photo library access for sharing</string>

    <!-- Firebase -->
    <key>FirebaseAppDelegateProxyEnabled</key>
    <false/>
    <key>FirebaseMessagingAutoInitEnabled</key>
    <true/>
</dict>
</plist>
```

### Appendix C: Route Constants

```dart
// lib/routing/route_constants.dart
class RouteConstants {
  RouteConstants._();

  // Route paths
  static const String splash = '/splash';
  static const String login = '/login';
  static const String home = '/home';
  
  // Product routes
  static const String products = '/products';
  static const String productDetail = '/product/:productId';
  static String productDetailPath(String productId) => '/product/$productId';
  
  // Order routes
  static const String orders = '/orders';
  static const String orderDetail = '/order/:orderId';
  static String orderDetailPath(String orderId) => '/order/$orderId';
  
  // Farm routes
  static const String farmAnimals = '/farm/animals';
  static const String animalDetail = '/farm/animal/:animalId';
  static String animalDetailPath(String animalId) => '/farm/animal/$animalId';
  
  // Profile routes
  static const String profile = '/profile';
  static const String settings = '/settings';
  static const String editProfile = '/profile/edit';
  
  // Checkout routes
  static const String cart = '/cart';
  static const String checkout = '/checkout';
  static const String payment = '/checkout/payment';
  static const String orderConfirmation = '/checkout/confirmation';
  
  // Other
  static const String notifications = '/notifications';
  static const String campaign = '/campaign/:campaignId';
  static String campaignPath(String campaignId) => '/campaign/$campaignId';

  // Query parameter keys
  static const String promoCodeParam = 'promo_code';
  static const String refParam = 'ref';
  static const String utmSourceParam = 'utm_source';
  static const String utmMediumParam = 'utm_medium';
  static const String utmCampaignParam = 'utm_campaign';
  static const String returnToParam = 'return_to';
}
```

### Appendix D: Analytics Integration

```dart
// lib/services/analytics_service.dart
import 'package:firebase_analytics/firebase_analytics.dart';

class AnalyticsService {
  static final FirebaseAnalytics _analytics = FirebaseAnalytics.instance;

  static Future<void> trackDeepLinkOpened(
    String url, {
    Map<String, String>? parameters,
  }) async {
    await _analytics.logEvent(
      name: 'deep_link_opened',
      parameters: {
        'url': url,
        'scheme': Uri.parse(url).scheme,
        'host': Uri.parse(url).host,
        'path': Uri.parse(url).path,
        ...?parameters,
      },
    );
  }

  static Future<void> trackScreenFromDeepLink(
    String screenName,
    String sourceUrl,
  }) async {
    await _analytics.logScreenView(
      screenName: screenName,
      screenClass: 'DeepLinkNavigation',
    );
    
    await _analytics.logEvent(
      name: 'screen_from_deep_link',
      parameters: {
        'screen': screenName,
        'source_url': sourceUrl,
      },
    );
  }

  static Future<void> trackEvent(
    String name, {
    Map<String, dynamic>? parameters,
  }) async {
    await _analytics.logEvent(
      name: name,
      parameters: parameters?.map(
        (key, value) => MapEntry(key, value.toString()),
      ),
    );
  }
}
```

### Appendix E: URL Pattern Reference Table

| Screen | Example URL | Required Params | Optional Params |
|--------|-------------|-----------------|-----------------|
| Home | `smartdairy://home` | - | - |
| Products | `smartdairy://products` | - | category, search |
| Product Detail | `smartdairy://product/123` | productId | promo_code, ref |
| Orders | `smartdairy://orders` | - | status, from, to |
| Order Detail | `smartdairy://order/ORD-001` | orderId | tracking |
| Farm Animals | `smartdairy://farm/animals` | - | status, breed |
| Animal Detail | `smartdairy://farm/animal/ANM-1` | animalId | tab |
| Profile | `smartdairy://profile` | - | - |
| Settings | `smartdairy://settings` | - | section |
| Cart | `smartdairy://cart` | - | promo_code |
| Checkout | `smartdairy://checkout` | - | - |
| Notifications | `smartdairy://notifications` | - | - |
| Campaign | `smartdairy://campaign/sale` | campaignId | utm_* |

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Mobile Lead | Initial document creation |

---

## Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Mobile Lead | | | |
| Tech Lead | | | |
| QA Lead | | | |

---

*End of Document H-009: Deep Linking & URL Handling*
