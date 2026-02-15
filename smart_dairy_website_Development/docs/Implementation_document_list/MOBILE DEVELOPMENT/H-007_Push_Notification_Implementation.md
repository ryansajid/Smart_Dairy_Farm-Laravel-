# H-007: Push Notification Implementation

## Smart Dairy Ltd. Mobile Application

---

**Document Information**

| Field | Value |
|-------|-------|
| **Document ID** | H-007 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Backend Lead |
| **Status** | Draft |
| **Classification** | Internal Use |

---

## Document Control

### Revision History

| Version | Date | Author | Changes | Approved By |
|---------|------|--------|---------|-------------|
| 1.0 | 2026-01-31 | Mobile Lead | Initial document creation | Backend Lead |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [FCM Setup](#2-fcm-setup)
3. [Android Configuration](#3-android-configuration)
4. [iOS Configuration](#4-ios-configuration)
5. [Flutter Integration](#5-flutter-integration)
6. [Notification Types](#6-notification-types)
7. [Message Handling](#7-message-handling)
8. [Local Notifications](#8-local-notifications)
9. [Notification Categories](#9-notification-categories)
10. [Deep Linking](#10-deep-linking-from-notifications)
11. [Token Management](#11-token-management)
12. [Rich Notifications](#12-rich-notifications)
13. [Notification UI](#13-notification-ui)
14. [Testing](#14-testing)
15. [Troubleshooting](#15-troubleshooting)
16. [Appendices](#16-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive implementation guidelines for integrating Firebase Cloud Messaging (FCM) push notifications into the Smart Dairy mobile application.

### 1.2 FCM Overview

Firebase Cloud Messaging supports two message types:
- **Notification Messages**: Auto-displayed by device when app is backgrounded
- **Data Messages**: Processed by client app for custom handling

### 1.3 Smart Dairy Notification Categories

| Category | Types | Priority |
|----------|-------|----------|
| Orders | Placed, Confirmed, Shipped, Delivered | High |
| Production | Collection alerts, Anomalies | High |
| Health | Vaccination, Health checks | Medium |
| Payments | Received, Pending, Failed | High |
| System | Sync, Maintenance | Low |

---

## 2. FCM Setup

### 2.1 Firebase Project Configuration

1. Create project at [Firebase Console](https://console.firebase.google.com/)
2. Add Android app with package `com.smartdairy.app`
3. Add iOS app with bundle ID `com.smartdairy.app`
4. Download configuration files:
   - Android: `google-services.json`
   - iOS: `GoogleService-Info.plist`

### 2.2 Server Credentials

```yaml
fcm:
  server_key: "YOUR_SERVER_KEY"
  sender_id: "YOUR_SENDER_ID"
  project_id: "smart-dairy-mobile"
```

---

## 3. Android Configuration

### 3.1 build.gradle (Project)

```gradle
buildscript {
    dependencies {
        classpath 'com.google.gms:google-services:4.3.15'
    }
}
```

### 3.2 build.gradle (App)

```gradle
plugins {
    id 'com.google.gms.google-services'
}

dependencies {
    implementation platform('com.google.firebase:firebase-bom:32.7.0')
    implementation 'com.google.firebase:firebase-messaging'
    implementation 'com.google.firebase:firebase-analytics'
}
```

### 3.3 AndroidManifest.xml

```xml
<manifest>
    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED" />
    <uses-permission android:name="android.permission.VIBRATE" />
    <uses-permission android:name="android.permission.POST_NOTIFICATIONS" />

    <application>
        <service
            android:name="com.google.firebase.messaging.FirebaseMessagingService"
            android:exported="false">
            <intent-filter>
                <action android:name="com.google.firebase.MESSAGING_EVENT" />
            </intent-filter>
        </service>
        
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_channel_id"
            android:value="smart_dairy_general" />
    </application>
</manifest>
```

---

## 4. iOS Configuration

### 4.1 Podfile

```ruby
platform :ios, '13.0'

target 'Runner' do
  use_frameworks!
  pod 'Firebase/Core'
  pod 'Firebase/Messaging'
end
```

### 4.2 AppDelegate.swift

```swift
import UIKit
import Flutter
import FirebaseCore
import FirebaseMessaging

@UIApplicationMain
@objc class AppDelegate: FlutterAppDelegate {
    override func application(
        _ application: UIApplication,
        didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?
    ) -> Bool {
        FirebaseApp.configure()
        Messaging.messaging().delegate = self
        
        if #available(iOS 10.0, *) {
            UNUserNotificationCenter.current().delegate = self
            let authOptions: UNAuthorizationOptions = [.alert, .badge, .sound]
            UNUserNotificationCenter.current().requestAuthorization(
                options: authOptions,
                completionHandler: { _, _ in }
            )
        }
        
        application.registerForRemoteNotifications()
        GeneratedPluginRegistrant.register(with: self)
        return super.application(application, didFinishLaunchingWithOptions: launchOptions)
    }
    
    override func application(_ application: UIApplication, didRegisterForRemoteNotificationsWithDeviceToken deviceToken: Data) {
        Messaging.messaging().apnsToken = deviceToken
    }
}

extension AppDelegate: MessagingDelegate {
    func messaging(_ messaging: Messaging, didReceiveRegistrationToken fcmToken: String?) {
        print("FCM Token: \(String(describing: fcmToken))")
    }
}

@available(iOS 10.0, *)
extension AppDelegate: UNUserNotificationCenterDelegate {
    func userNotificationCenter(_ center: UNUserNotificationCenter, willPresent notification: UNNotification, withCompletionHandler completionHandler: @escaping (UNNotificationPresentationOptions) -> Void) {
        completionHandler([[.alert, .sound, .badge]])
    }
}
```

### 4.3 APNs Certificate Setup

1. Enable Push Notifications in Apple Developer Portal
2. Create SSL certificate for APNs
3. Export as .p12 file
4. Upload to Firebase Console

---

## 5. Flutter Integration

### 5.1 Dependencies

```yaml
# pubspec.yaml
dependencies:
  firebase_core: ^2.24.2
  firebase_messaging: ^14.7.10
  flutter_local_notifications: ^16.3.0
  shared_preferences: ^2.2.2
```

### 5.2 Main.dart

```dart
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';

@pragma('vm:entry-point')
Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();
  await NotificationService.instance.handleBackgroundMessage(message);
}

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await Firebase.initializeApp();
  FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);
  await NotificationService.instance.initialize();
  runApp(const SmartDairyApp());
}
```

---

## 6. Notification Types

### 6.1 FCM Payload Examples

**Order Notification:**
```json
{
  "message": {
    "token": "device_token",
    "notification": {
      "title": "Order Confirmed",
      "body": "Order #12345 confirmed!"
    },
    "data": {
      "type": "order_confirmed",
      "order_id": "12345"
    },
    "android": {
      "priority": "high",
      "notification": { "channel_id": "smart_dairy_orders" }
    },
    "apns": {
      "headers": { "apns-priority": "10" },
      "payload": {
        "aps": {
          "alert": { "title": "Order Confirmed", "body": "Order #12345" },
          "badge": 1,
          "sound": "default"
        }
      }
    }
  }
}
```

**Data Message (Background):**
```json
{
  "message": {
    "token": "device_token",
    "data": {
      "type": "sync_completed",
      "records_synced": "150"
    },
    "android": { "priority": "normal" }
  }
}
```

---

## 7. Message Handling

### 7.1 Notification Service Implementation

```dart
// lib/services/notification_service.dart
import 'dart:convert';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';

class NotificationService {
  static final NotificationService _instance = NotificationService._internal();
  factory NotificationService() => _instance;
  NotificationService._internal();
  
  static NotificationService get instance => _instance;
  
  final FirebaseMessaging _messaging = FirebaseMessaging.instance;
  final FlutterLocalNotificationsPlugin _localNotifications = 
      FlutterLocalNotificationsPlugin();
  
  final StreamController<Map<String, dynamic>> _notificationTapController = 
      StreamController<Map<String, dynamic>>.broadcast();
  
  Stream<Map<String, dynamic>> get onNotificationTap => _notificationTapController.stream;

  Future<void> initialize() async {
    // Request permissions
    await _requestPermissions();
    
    // Initialize local notifications
    await _initLocalNotifications();
    
    // Setup FCM handlers
    _setupFCMHandlers();
    
    // Check initial message (app opened from terminated state)
    await _checkInitialMessage();
  }
  
  Future<void> _requestPermissions() async {
    await _messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
      provisional: false,
    );
  }
  
  Future<void> _initLocalNotifications() async {
    const AndroidInitializationSettings androidSettings = 
        AndroidInitializationSettings('@drawable/ic_notification');
    
    final DarwinInitializationSettings iosSettings = DarwinInitializationSettings(
      requestAlertPermission: true,
      requestBadgePermission: true,
      requestSoundPermission: true,
    );
    
    final InitializationSettings initSettings = InitializationSettings(
      android: androidSettings,
      iOS: iosSettings,
    );
    
    await _localNotifications.initialize(
      initSettings,
      onDidReceiveNotificationResponse: (response) {
        if (response.payload != null) {
          _notificationTapController.add(jsonDecode(response.payload!));
        }
      },
    );
    
    // Create notification channels
    await _createNotificationChannels();
  }
  
  Future<void> _createNotificationChannels() async {
    const channels = [
      AndroidNotificationChannel('smart_dairy_orders', 'Order Updates', 
          importance: Importance.high),
      AndroidNotificationChannel('smart_dairy_production', 'Production Alerts',
          importance: Importance.high),
      AndroidNotificationChannel('smart_dairy_health', 'Health Reminders'),
      AndroidNotificationChannel('smart_dairy_payment', 'Payment Notifications',
          importance: Importance.high),
      AndroidNotificationChannel('smart_dairy_sync', 'Sync Status',
          importance: Importance.low),
      AndroidNotificationChannel('smart_dairy_system', 'System Notifications'),
    ];
    
    final androidPlugin = _localNotifications
        .resolvePlatformSpecificImplementation<AndroidFlutterLocalNotificationsPlugin>();
    
    for (final channel in channels) {
      await androidPlugin?.createNotificationChannel(channel);
    }
  }
  
  void _setupFCMHandlers() {
    // Foreground messages
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      _handleForegroundMessage(message);
    });
    
    // App opened from background
    FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
      _handleNotificationTap(message.data);
    });
  }
  
  Future<void> _checkInitialMessage() async {
    final RemoteMessage? initialMessage = await _messaging.getInitialMessage();
    if (initialMessage != null) {
      _handleNotificationTap(initialMessage.data);
    }
  }
  
  void _handleForegroundMessage(RemoteMessage message) {
    if (message.notification != null) {
      _showLocalNotification(message);
    }
    
    if (message.data.isNotEmpty) {
      _handleDataMessage(message.data);
    }
  }
  
  Future<void> _showLocalNotification(RemoteMessage message) async {
    final notification = message.notification;
    final android = message.notification?.android;
    
    final androidDetails = AndroidNotificationDetails(
      message.data['channel_id'] ?? 'smart_dairy_general',
      'Smart Dairy',
      importance: Importance.high,
      priority: Priority.high,
      icon: android?.smallIcon ?? '@drawable/ic_notification',
    );
    
    const iosDetails = DarwinNotificationDetails(
      presentAlert: true,
      presentBadge: true,
      presentSound: true,
    );
    
    await _localNotifications.show(
      notification.hashCode,
      notification?.title,
      notification?.body,
      NotificationDetails(android: androidDetails, iOS: iosDetails),
      payload: jsonEncode(message.data),
    );
  }
  
  void _handleDataMessage(Map<String, dynamic> data) {
    final String type = data['type'] ?? '';
    
    switch (type) {
      case 'sync_completed':
        EventBus.instance.fire(SyncCompletedEvent(data));
        break;
      case 'production_anomaly':
        EventBus.instance.fire(ProductionAnomalyEvent(data));
        break;
      default:
        debugPrint('Unknown data message type: $type');
    }
  }
  
  void _handleNotificationTap(Map<String, dynamic> data) {
    _notificationTapController.add(data);
  }
  
  Future<void> handleBackgroundMessage(RemoteMessage message) async {
    final String type = message.data['type'] ?? '';
    
    switch (type) {
      case 'milk_collection_alert':
        await _cacheAlert(message.data);
        break;
      case 'sync_trigger':
        await _performBackgroundSync();
        break;
    }
  }
  
  Future<void> _cacheAlert(Map<String, dynamic> data) async {
    final prefs = await SharedPreferences.getInstance();
    final alerts = prefs.getStringList('pending_alerts') ?? [];
    alerts.add(jsonEncode(data));
    await prefs.setStringList('pending_alerts', alerts);
  }
  
  Future<void> _performBackgroundSync() async {
    // Background sync implementation
  }
}
```

---

## 8. Local Notifications

### 8.1 Scheduled Notifications

```dart
class LocalNotificationService {
  Future<void> scheduleHealthReminder({
    required String cattleId,
    required String cattleName,
    required DateTime scheduledDate,
    required String checkType,
  }) async {
    await _localNotifications.zonedSchedule(
      'health_$cattleId'.hashCode,
      'Health Check Due',
      '$checkType check is due for $cattleName',
      tz.TZDateTime.from(scheduledDate, tz.local),
      NotificationDetails(
        android: AndroidNotificationDetails(
          'smart_dairy_health',
          'Health Reminders',
          importance: Importance.defaultImportance,
        ),
        iOS: const DarwinNotificationDetails(),
      ),
      androidAllowWhileIdle: true,
      uiLocalNotificationDateInterpretation: 
          UILocalNotificationDateInterpretation.absoluteTime,
      payload: jsonEncode({
        'type': 'health_check_due',
        'cattle_id': cattleId,
      }),
    );
  }
  
  Future<void> scheduleVaccinationReminder({
    required String cattleId,
    required String vaccineName,
    required DateTime vaccinationDate,
    int reminderDays = 1,
  }) async {
    final reminderDate = vaccinationDate.subtract(Duration(days: reminderDays));
    
    await scheduleHealthReminder(
      cattleId: cattleId,
      cattleName: cattleId,
      scheduledDate: reminderDate,
      checkType: '$vaccineName vaccination',
    );
  }
}
```

---

## 9. Notification Categories

### 9.1 Category Handlers

```dart
class NotificationCategoryHandler {
  static void handleNotification(Map<String, dynamic> data) {
    final String category = data['category'] ?? '';
    
    switch (category) {
      case 'order':
        OrderNotificationHandler.handle(data);
        break;
      case 'production':
        ProductionNotificationHandler.handle(data);
        break;
      case 'health':
        HealthNotificationHandler.handle(data);
        break;
      case 'payment':
        PaymentNotificationHandler.handle(data);
        break;
      case 'system':
        SystemNotificationHandler.handle(data);
        break;
    }
  }
}

class OrderNotificationHandler {
  static void handle(Map<String, dynamic> data) {
    final String subType = data['sub_type'] ?? '';
    
    switch (subType) {
      case 'order_placed':
        _showOrderPlaced(data);
        break;
      case 'order_confirmed':
        _showOrderConfirmed(data);
        break;
      case 'order_shipped':
        _showOrderShipped(data);
        break;
      case 'order_delivered':
        _showOrderDelivered(data);
        break;
    }
  }
  
  static void _showOrderPlaced(Map<String, dynamic> data) {
    NotificationService.instance.showNotification(
      title: '🛒 Order Placed',
      body: 'Order #${data['order_id']} for ₹${data['amount']} placed',
      channelId: 'smart_dairy_orders',
      payload: data,
    );
  }
  
  static void _showOrderConfirmed(Map<String, dynamic> data) {
    NotificationService.instance.showNotification(
      title: '✅ Order Confirmed',
      body: 'Order #${data['order_id']} confirmed! Delivery: ${data['expected_delivery']}',
      channelId: 'smart_dairy_orders',
      payload: data,
    );
  }
  
  static void _showOrderShipped(Map<String, dynamic> data) {
    NotificationService.instance.showNotification(
      title: '🚚 Order Shipped',
      body: 'Order #${data['order_id']} shipped. Tracking: ${data['tracking_number']}',
      channelId: 'smart_dairy_orders',
      payload: data,
    );
  }
  
  static void _showOrderDelivered(Map<String, dynamic> data) {
    NotificationService.instance.showNotification(
      title: '📦 Order Delivered',
      body: 'Order #${data['order_id']} delivered. Rate your experience!',
      channelId: 'smart_dairy_orders',
      payload: data,
    );
  }
}
```

---

## 10. Deep Linking

### 10.1 Deep Link Service

```dart
class DeepLinkService {
  static final DeepLinkService _instance = DeepLinkService._internal();
  factory DeepLinkService() => _instance;
  DeepLinkService._internal();
  
  static DeepLinkService get instance => _instance;
  
  void handleDeepLink(String deepLink) {
    final uri = Uri.parse(deepLink);
    if (uri.scheme != 'smartdairy') return;
    
    switch (uri.host) {
      case 'orders':
        _navigateToOrder(uri.pathSegments);
        break;
      case 'payments':
        _navigateToPayment(uri.pathSegments);
        break;
      case 'production':
        _navigateToProduction(uri.pathSegments);
        break;
      case 'health':
        _navigateToHealth(uri.pathSegments);
        break;
    }
  }
  
  void _navigateToOrder(List<String> segments) {
    if (segments.isEmpty) {
      NavigationService.instance.navigateTo('/orders');
    } else {
      final orderId = segments[0];
      NavigationService.instance.navigateTo('/orders/$orderId');
    }
  }
  
  void _navigateToPayment(List<String> segments) {
    if (segments.isEmpty) {
      NavigationService.instance.navigateTo('/payments');
    } else {
      NavigationService.instance.navigateTo('/payments/${segments[0]}');
    }
  }
  
  void _navigateToProduction(List<String> segments) {
    if (segments.isEmpty) {
      NavigationService.instance.navigateTo('/production');
    } else {
      NavigationService.instance.navigateTo('/production/${segments[0]}');
    }
  }
  
  void _navigateToHealth(List<String> segments) {
    if (segments.isEmpty) {
      NavigationService.instance.navigateTo('/health');
    } else {
      NavigationService.instance.navigateTo('/health/${segments[0]}');
    }
  }
}
```

---

## 11. Token Management

### 11.1 Token Service

```dart
class TokenService {
  static final TokenService _instance = TokenService._internal();
  factory TokenService() => _instance;
  TokenService._internal();
  
  final FirebaseMessaging _messaging = FirebaseMessaging.instance;
  final StreamController<String> _tokenRefreshController = 
      StreamController<String>.broadcast();
  
  Stream<String> get onTokenRefresh => _tokenRefreshController.stream;
  
  Future<void> initialize() async {
    // Get initial token
    final token = await _messaging.getToken();
    if (token != null) {
      await _syncTokenToServer(token);
    }
    
    // Listen for token refresh
    _messaging.onTokenRefresh.listen((String token) {
      _tokenRefreshController.add(token);
      _syncTokenToServer(token);
    });
  }
  
  Future<void> _syncTokenToServer(String token) async {
    final deviceInfo = await _getDeviceInfo();
    
    await http.post(
      Uri.parse('${ApiConfig.baseUrl}/notifications/register-device'),
      headers: await ApiConfig.getHeaders(),
      body: jsonEncode({
        'fcm_token': token,
        'device_type': deviceInfo['device_type'],
        'platform': deviceInfo['platform'],
        'app_version': deviceInfo['app_version'],
      }),
    );
  }
  
  Future<Map<String, String>> _getDeviceInfo() async {
    final packageInfo = await PackageInfo.fromPlatform();
    return {
      'device_type': Platform.isAndroid ? 'android' : 'ios',
      'platform': Platform.operatingSystem,
      'app_version': packageInfo.version,
    };
  }
  
  Future<void> subscribeToTopic(String topic) async {
    await _messaging.subscribeToTopic(topic);
  }
  
  Future<void> unsubscribeFromTopic(String topic) async {
    await _messaging.unsubscribeFromTopic(topic);
  }
}
```

---

## 12. Rich Notifications

### 12.1 Image Notifications

```dart
class RichNotificationService {
  Future<void> showImageNotification({
    required String title,
    required String body,
    required String imageUrl,
  }) async {
    final bigPictureStyle = BigPictureStyleInformation(
      FilePathAndroidBitmap(await _downloadImage(imageUrl) ?? ''),
      largeIcon: const DrawableResourceAndroidBitmap('@drawable/ic_notification'),
    );
    
    await _localNotifications.show(
      DateTime.now().millisecond,
      title,
      body,
      NotificationDetails(
        android: AndroidNotificationDetails(
          'smart_dairy_rich',
          'Rich Notifications',
          styleInformation: bigPictureStyle,
        ),
      ),
    );
  }
  
  Future<String?> _downloadImage(String url) async {
    final response = await http.get(Uri.parse(url));
    if (response.statusCode == 200) {
      final dir = await getTemporaryDirectory();
      final file = File('${dir.path}/notif_${DateTime.now().millisecondsSinceEpoch}.jpg');
      await file.writeAsBytes(response.bodyBytes);
      return file.path;
    }
    return null;
  }
}
```

---

## 13. Testing

### 13.1 Test Script

```bash
#!/bin/bash
SERVER_KEY="YOUR_SERVER_KEY"
FCM_TOKEN="TEST_DEVICE_TOKEN"

# Test Order Notification
curl -X POST https://fcm.googleapis.com/fcm/send \
  -H "Authorization: key=$SERVER_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "'$FCM_TOKEN'",
    "notification": {
      "title": "Order Confirmed",
      "body": "Order #12345 has been confirmed"
    },
    "data": {
      "type": "order_confirmed",
      "order_id": "12345"
    }
  }'

# Test Production Alert
curl -X POST https://fcm.googleapis.com/fcm/send \
  -H "Authorization: key=$SERVER_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "'$FCM_TOKEN'",
    "data": {
      "type": "milk_collection_alert",
      "collection_id": "COL-001",
      "quantity": "150.5"
    }
  }'
```

### 13.2 Flutter Test

```dart
void main() {
  group('NotificationService', () {
    test('requests permissions on initialize', () async {
      final service = NotificationService();
      await service.initialize();
      // Verify permissions requested
    });
    
    test('handles FCM token refresh', () async {
      final service = TokenService();
      await service.initialize();
      // Verify token sync
    });
  });
}
```

---

## 14. Troubleshooting

### 14.1 Common Issues

| Issue | Cause | Solution |
|-------|-------|----------|
| Notifications not received | Incorrect FCM token | Verify token, check server key |
| Foreground notifications not showing | Missing local notification setup | Implement local notifications |
| iOS notifications not working | APNs not configured | Upload certificates to Firebase |
| Background messages not received | Missing background handler | Register background handler |
| Token not syncing | Network issues | Implement retry logic |

### 14.2 Debug Checklist

1. Verify FCM token is valid
2. Check notification permissions granted
3. Verify server key is correct
4. Check Android notification channels created
5. Verify iOS APNs certificates uploaded
6. Check network connectivity
7. Review Firebase Console logs

---

## 15. Appendices

### Appendix A: Complete Implementation Code

```dart
// lib/models/notification_model.dart
class NotificationModel {
  final String id;
  final String title;
  final String body;
  final String category;
  final bool isRead;
  final DateTime timestamp;
  final Map<String, dynamic>? payload;

  NotificationModel({
    required this.id,
    required this.title,
    required this.body,
    required this.category,
    this.isRead = false,
    required this.timestamp,
    this.payload,
  });
}

// lib/services/navigation_service.dart
class NavigationService {
  static final NavigationService _instance = NavigationService._internal();
  factory NavigationService() => _instance;
  NavigationService._internal();
  
  static NavigationService get instance => _instance;
  
  final GlobalKey<NavigatorState> navigatorKey = GlobalKey<NavigatorState>();
  
  void navigateTo(String route) {
    navigatorKey.currentState?.pushNamed(route);
  }
}

// lib/utils/event_bus.dart
class EventBus {
  static final EventBus _instance = EventBus._internal();
  factory EventBus() => _instance;
  EventBus._internal();
  
  static EventBus get instance => _instance;
  
  final StreamController _controller = StreamController.broadcast();
  
  Stream<T> on<T>() => _controller.stream.where((e) => e is T).cast<T>();
  
  void fire(event) => _controller.add(event);
}

class SyncCompletedEvent {
  final Map<String, dynamic> data;
  SyncCompletedEvent(this.data);
}

class ProductionAnomalyEvent {
  final Map<String, dynamic> data;
  ProductionAnomalyEvent(this.data);
}
```

### Appendix B: Backend API Endpoints

```yaml
# Device Registration
POST /api/v1/notifications/register-device
Request:
  fcm_token: string
  device_type: string
  platform: string
  app_version: string

# Device Unregistration  
POST /api/v1/notifications/unregister-device
Request:
  fcm_token: string

# Send Test Notification
POST /api/v1/notifications/test
Request:
  user_id: string
  notification_type: string
```

### Appendix C: iOS APNs Certificate Generation

```bash
# Step 1: Create Certificate Signing Request (CSR)
# - Open Keychain Access
# - Certificate Assistant > Request Certificate from Authority
# - Save to disk

# Step 2: Create APNs Certificate (Apple Developer Portal)
# - Certificates, Identifiers & Profiles
# - Create new certificate (Apple Push Notification service SSL)
# - Upload CSR
# - Download .cer file

# Step 3: Export P12
# - Double-click .cer to add to Keychain
# - Right-click certificate > Export
# - Save as .p12 with password

# Step 4: Upload to Firebase
# - Firebase Console > Project Settings > Cloud Messaging
# - Upload .p12 file with password
```

---

**End of Document H-007: Push Notification Implementation**

---

*Document Control: Smart Dairy Ltd.*
*Classification: Internal Use*
*Last Updated: January 31, 2026*
