# Firebase Integration Guide for Smart Dairy Ltd.

| **Document ID** | E-010 |
|-----------------|-------|
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Status** | Draft |
| **Classification** | Internal |

---

## Document History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Integration Engineer | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Firebase Project Setup](#2-firebase-project-setup)
3. [Firebase Authentication](#3-firebase-authentication)
4. [Cloud Firestore](#4-cloud-firestore)
5. [Firebase Realtime Database](#5-firebase-realtime-database)
6. [Cloud Messaging (FCM)](#6-cloud-messaging-fcm)
7. [Firebase Storage](#7-firebase-storage)
8. [Firebase Analytics](#8-firebase-analytics)
9. [Crashlytics](#9-crashlytics)
10. [Remote Config](#10-remote-config)
11. [Implementation - Flutter Integration](#11-implementation---flutter-integration)
12. [Security Rules](#12-security-rules)
13. [Backend Integration - Python Admin SDK](#13-backend-integration---python-admin-sdk)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Overview

Firebase is Google's comprehensive mobile and web application development platform that provides backend services, real-time databases, authentication, analytics, and more. For Smart Dairy Ltd., Firebase serves as the backbone for real-time features, user authentication, push notifications, and data synchronization across mobile and web platforms.

### 1.2 Firebase Services for Smart Dairy

| Service | Purpose | Smart Dairy Use Case |
|---------|---------|---------------------|
| **Authentication** | User identity management | Quick onboarding with phone OTP, email-password, anonymous auth |
| **Cloud Firestore** | NoSQL document database | Shopping cart with offline sync, user profiles, order history |
| **Realtime Database** | Real-time data synchronization | Live order tracking, real-time inventory updates |
| **Cloud Messaging** | Push notifications | Order status updates, promotional alerts, delivery notifications |
| **Storage** | File and image hosting | Product images, user profile pictures, receipt PDFs |
| **Analytics** | User behavior tracking | Purchase patterns, app usage, feature adoption |
| **Crashlytics** | Crash reporting | App stability monitoring, issue prioritization |
| **Remote Config** | Feature flags | A/B testing, gradual feature rollouts, configuration updates |

### 1.3 Architecture Overview

```
+------------------------------------------------------------------+
|                      SMART DAIRY APP                              |
|  +------------+  +------------+  +------------+  +------------+  |
|  |   Flutter  |  |   Flutter  |  |   Flutter  |  |     Web    |  |
|  |    iOS     |  |   Android  |  |     Web    |  |   Portal   |  |
|  +------+-----+  +------+-----+  +------+-----+  +------+-----+  |
+--------|-----------------|-----------------|-------------|--------+
         |                 |                 |             |
         +-----------------+-----------------+-------------+
                                   |
                        +----------+-----------+
                        |    FIREBASE SDK      |
                        |  (Auth, Firestore,   |
                        |   FCM, Storage)      |
                        +----------+-----------+
                                   |
       +---------------------------+----------------------------+
       |                           |                            |
+------v-------+     +-------------v-------------+   +----------v----------+
|   Cloud      |     |    Cloud Function         |   |    Python           |
|  Firestore   |     |    (Firebase)             |   |    Backend          |
|              |     |                           |   |    (Admin SDK)      |
|  Users       |     |  Token Refresh            |   |                     |
|  Orders      |     |  Notifications            |   |  Verification       |
|  Cart        |     |  Triggers                 |   |  Reports            |
|  Products    |     |                           |   |  Admin Ops          |
+--------------+     +---------------------------+   +---------------------+
```

### 1.4 Benefits for Smart Dairy

- **Rapid Development**: Pre-built authentication and database solutions reduce development time
- **Offline Capabilities**: Firestore enables offline-first architecture for shopping cart
- **Real-time Updates**: Instant synchronization across all user devices
- **Scalability**: Automatic scaling handles traffic spikes during promotions
- **Cost Efficiency**: Pay-as-you-go pricing aligns with business growth
- **Cross-platform**: Single codebase for iOS, Android, and Web

---

## 2. Firebase Project Setup

### 2.1 Creating a Firebase Project

#### Step 1: Create Project in Firebase Console

1. Navigate to [Firebase Console](https://console.firebase.google.com/)
2. Click "Add Project"
3. Enter project name: `smart-dairy-ltd`
4. Enable Google Analytics for this project
5. Select or create Analytics account
6. Accept terms and create project

#### Step 2: Configure Project Settings

```
Project ID: smart-dairy-ltd
Region: asia-south1 (Mumbai) - closest to Nepal
Default GCP resource location: asia-south1
```

### 2.2 Adding Android App

#### Configuration Steps:

1. In Firebase Console, click "Add App" → "Android"
2. Register app with package name: `com.smartdairy.mobile`
3. Add nickname: "Smart Dairy Android"
4. Download `google-services.json`
5. Place file in Flutter project: `android/app/google-services.json`

#### Android Configuration:

**android/build.gradle:**
```gradle
buildscript {
    dependencies {
        classpath 'com.google.gms:google-services:4.4.0'
    }
}
```

**android/app/build.gradle:**
```gradle
plugins {
    id 'com.android.application'
    id 'kotlin-android'
    id 'com.google.gms.google-services'
}

android {
    defaultConfig {
        minSdkVersion 21
        targetSdkVersion 34
        multiDexEnabled true
    }
}

dependencies {
    implementation platform('com.google.firebase:firebase-bom:32.7.0')
    implementation 'com.google.firebase:firebase-analytics'
    implementation 'androidx.multidex:multidex:2.0.1'
}
```

**android/app/src/main/AndroidManifest.xml:**
```xml
<manifest xmlns:android="http://schemas.android.com/apk/res/android">
    <application
        android:label="Smart Dairy"
        android:name="${applicationName}"
        android:icon="@mipmap/ic_launcher">
        
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_icon"
            android:resource="@drawable/ic_notification" />
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_color"
            android:resource="@color/notification_color" />
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_channel_id"
            android:value="smart_dairy_channel" />
            
        <activity android:name=".MainActivity" android:exported="true">
            <intent-filter>
                <action android:name="android.intent.action.MAIN"/>
                <category android:name="android.intent.category.LAUNCHER"/>
            </intent-filter>
        </activity>
    </application>
</manifest>
```

### 2.3 Adding iOS App

#### Configuration Steps:

1. In Firebase Console, click "Add App" → "iOS"
2. Register app with Bundle ID: `com.smartdairy.mobile`
3. Download `GoogleService-Info.plist`
4. Place file in Flutter project: `ios/Runner/GoogleService-Info.plist`

#### iOS Configuration:

**ios/Runner/AppDelegate.swift:**
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
    
    UNUserNotificationCenter.current().delegate = self
    let authOptions: UNAuthorizationOptions = [.alert, .badge, .sound]
    UNUserNotificationCenter.current().requestAuthorization(
      options: authOptions,
      completionHandler: { _, _ in }
    )
    application.registerForRemoteNotifications()
    
    GeneratedPluginRegistrant.register(with: self)
    return super.application(application, didFinishLaunchingWithOptions: launchOptions)
  }
  
  override func application(
    _ application: UIApplication,
    didRegisterForRemoteNotificationsWithDeviceToken deviceToken: Data
  ) {
    Messaging.messaging().apnsToken = deviceToken
    super.application(application, didRegisterForRemoteNotificationsWithDeviceToken: deviceToken)
  }
}
```

### 2.4 Adding Web App

**web/index.html:**
```html
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Smart Dairy</title>
  
  <script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-auth.js"></script>
  <script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-firestore.js"></script>
  <script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging.js"></script>
  <script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-storage.js"></script>
  <script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-analytics.js"></script>
  
  <script>
    const firebaseConfig = {
      apiKey: "AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
      authDomain: "smart-dairy-ltd.firebaseapp.com",
      projectId: "smart-dairy-ltd",
      storageBucket: "smart-dairy-ltd.appspot.com",
      messagingSenderId: "123456789012",
      appId: "1:123456789012:web:abcdef1234567890",
      measurementId: "G-XXXXXXXXXX"
    };
    firebase.initializeApp(firebaseConfig);
    firebase.analytics();
  </script>
  
  <script src="flutter.js" defer></script>
</head>
<body>
  <script>
    window.addEventListener('load', function(ev) {
      _flutter.loader.loadEntrypoint({
        serviceWorker: {
          serviceWorkerVersion: serviceWorkerVersion,
        },
        onEntrypointLoaded: function(engineInitializer) {
          engineInitializer.initializeEngine().then(function(appRunner) {
            appRunner.runApp();
          });
        }
      });
    });
  </script>
</body>
</html>
```

**web/firebase-messaging-sw.js:**
```javascript
importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: "AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
  authDomain: "smart-dairy-ltd.firebaseapp.com",
  projectId: "smart-dairy-ltd",
  storageBucket: "smart-dairy-ltd.appspot.com",
  messagingSenderId: "123456789012",
  appId: "1:123456789012:web:abcdef1234567890"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/icons/Icon-192.png',
    data: payload.data
  };
  self.registration.showNotification(notificationTitle, notificationOptions);
});
```

---

## 3. Firebase Authentication

### 3.1 Authentication Methods Overview

| Method | Use Case | Implementation Complexity |
|--------|----------|--------------------------|
| **Anonymous** | Quick app exploration without signup | Low |
| **Phone OTP** | Primary method for Nepali users | Medium |
| **Email-Password** | Users preferring traditional method | Low |
| **Google Sign-In** | Quick signup for Android users | Medium |
| **Apple Sign-In** | Required for iOS App Store | Medium |

### 3.2 Anonymous Authentication

```dart
import 'package:firebase_auth/firebase_auth.dart';

class AnonymousAuthService {
  final FirebaseAuth _auth = FirebaseAuth.instance;

  Future<User?> signInAnonymously() async {
    try {
      UserCredential result = await _auth.signInAnonymously();
      User? user = result.user;
      await _storeAnonymousUserId(user?.uid);
      return user;
    } on FirebaseAuthException catch (e) {
      print('Anonymous sign-in error: ${e.code} - ${e.message}');
      return null;
    }
  }

  Future<User?> upgradeAnonymousToEmailPassword(
    String email,
    String password,
  ) async {
    try {
      User? currentUser = _auth.currentUser;
      if (currentUser == null || !currentUser.isAnonymous) {
        throw Exception('No anonymous user found');
      }

      AuthCredential credential = EmailAuthProvider.credential(
        email: email,
        password: password,
      );

      UserCredential result = await currentUser.linkWithCredential(credential);
      await _updateUserProfile(result.user);
      return result.user;
    } on FirebaseAuthException catch (e) {
      if (e.code == 'email-already-in-use') {
        return await _mergeAnonymousWithExisting(email, password);
      }
      return null;
    }
  }

  Future<void> _storeAnonymousUserId(String? uid) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('anonymous_user_id', uid ?? '');
  }

  Future<void> _updateUserProfile(User? user) async {
    // Update Firestore user document
  }
}
```

### 3.3 Phone OTP Authentication

```dart
class PhoneAuthService {
  final FirebaseAuth _auth = FirebaseAuth.instance;
  String? _verificationId;
  int? _resendToken;

  Future<void> sendOTP(
    String phoneNumber, {
    required Function(String verificationId) onCodeSent,
    required Function(FirebaseAuthException error) onError,
  }) async {
    try {
      String formattedPhone = _formatNepaliPhone(phoneNumber);

      await _auth.verifyPhoneNumber(
        phoneNumber: formattedPhone,
        timeout: const Duration(seconds: 60),
        verificationCompleted: (PhoneAuthCredential credential) async {
          await _auth.signInWithCredential(credential);
        },
        verificationFailed: (FirebaseAuthException e) {
          onError(e);
        },
        codeSent: (String verificationId, int? resendToken) {
          _verificationId = verificationId;
          _resendToken = resendToken;
          onCodeSent(verificationId);
        },
        codeAutoRetrievalTimeout: (String verificationId) {
          _verificationId = verificationId;
        },
      );
    } catch (e) {
      onError(FirebaseAuthException(
        code: 'unknown-error',
        message: 'Failed to send OTP',
      ));
    }
  }

  Future<UserCredential?> verifyOTP(String smsCode) async {
    try {
      PhoneAuthCredential credential = PhoneAuthProvider.credential(
        verificationId: _verificationId!,
        smsCode: smsCode,
      );

      UserCredential result = await _auth.signInWithCredential(credential);
      await _createOrUpdateUserDocument(result.user);
      return result;
    } on FirebaseAuthException catch (e) {
      return null;
    }
  }

  String _formatNepaliPhone(String phone) {
    String digitsOnly = phone.replaceAll(RegExp(r'[^\d]'), '');
    if (digitsOnly.startsWith('98') && digitsOnly.length == 10) {
      return '+977$digitsOnly';
    } else if (digitsOnly.startsWith('977')) {
      return '+$digitsOnly';
    }
    throw FormatException('Invalid phone number format');
  }

  Future<void> _createOrUpdateUserDocument(User? user) async {
    if (user == null) return;
    final userDoc = FirebaseFirestore.instance.collection('users').doc(user.uid);
    final userData = {
      'uid': user.uid,
      'phoneNumber': user.phoneNumber,
      'isAnonymous': false,
      'createdAt': FieldValue.serverTimestamp(),
      'lastLoginAt': FieldValue.serverTimestamp(),
      'authProvider': 'phone',
    };
    await userDoc.set(userData, SetOptions(merge: true));
  }
}
```

### 3.4 Email-Password Authentication

```dart
class EmailAuthService {
  final FirebaseAuth _auth = FirebaseAuth.instance;

  Future<UserCredential?> signUpWithEmail(
    String email,
    String password,
    String fullName,
  ) async {
    try {
      UserCredential result = await _auth.createUserWithEmailAndPassword(
        email: email,
        password: password,
      );
      await result.user?.updateDisplayName(fullName);
      await result.user?.sendEmailVerification();
      await _createUserDocument(result.user, fullName: fullName);
      return result;
    } on FirebaseAuthException catch (e) {
      throw _handleAuthError(e);
    }
  }

  Future<UserCredential?> signInWithEmail(
    String email,
    String password,
  ) async {
    try {
      UserCredential result = await _auth.signInWithEmailAndPassword(
        email: email,
        password: password,
      );
      if (!result.user!.emailVerified) {
        throw FirebaseAuthException(
          code: 'email-not-verified',
          message: 'Please verify your email before signing in',
        );
      }
      await _updateLastLogin(result.user);
      return result;
    } on FirebaseAuthException catch (e) {
      throw _handleAuthError(e);
    }
  }

  Exception _handleAuthError(FirebaseAuthException e) {
    switch (e.code) {
      case 'weak-password':
        return Exception('Password must be at least 6 characters');
      case 'email-already-in-use':
        return Exception('An account already exists with this email');
      case 'invalid-email':
        return Exception('Invalid email address');
      case 'user-not-found':
        return Exception('No account found with this email');
      case 'wrong-password':
        return Exception('Incorrect password');
      case 'too-many-requests':
        return Exception('Too many attempts. Please try again later');
      default:
        return Exception(e.message ?? 'Authentication failed');
    }
  }
}
```

### 3.5 Authentication State Management

```dart
class AuthStateProvider extends ChangeNotifier {
  final FirebaseAuth _auth = FirebaseAuth.instance;
  User? _user;
  StreamSubscription<User?>? _authSubscription;

  User? get user => _user;
  bool get isAuthenticated => _user != null;
  bool get isAnonymous => _user?.isAnonymous ?? false;

  AuthStateProvider() {
    _authSubscription = _auth.authStateChanges().listen((User? user) {
      _user = user;
      notifyListeners();
      if (user != null) {
        FirebaseAnalytics.instance.setUserId(id: user.uid);
        FirebaseAnalytics.instance.logEvent(
          name: user.isAnonymous ? 'anonymous_login' : 'user_login',
        );
      }
    });
  }

  Future<void> signOut() async {
    await _auth.signOut();
  }

  Future<String?> getIdToken() async {
    return await _user?.getIdToken();
  }
}
```

---

## 4. Cloud Firestore

### 4.1 Database Structure

```
users/{userId}
  - uid: string
  - phoneNumber: string
  - email: string
  - fullName: string
  - addresses: map[]
  - createdAt: timestamp
  - fcmTokens: string[]

products/{productId}
  - id: string
  - name: string
  - description: string
  - categoryId: string
  - price: number
  - stockQuantity: number
  - images: string[]
  - isActive: boolean

orders/{orderId}
  - id: string
  - userId: string
  - items: map[]
  - totalAmount: number
  - status: string
  - createdAt: timestamp
  
  order_status_updates/{updateId}
    - status: string
    - message: string
    - timestamp: timestamp

carts/{userId}
  - userId: string
  - items: map[]
  - totalAmount: number
  - updatedAt: timestamp
```

### 4.2 Offline-Capable Shopping Cart

```dart
class CartService {
  final FirebaseFirestore _firestore = FirebaseFirestore.instance;
  final String _userId;

  static Future<void> initialize() async {
    await FirebaseFirestore.instance.enablePersistence(
      const PersistenceSettings(synchronizeTabs: true),
    );
  }

  CartService(this._userId) {
    _firestore.settings = Settings(
      persistenceEnabled: true,
      cacheSizeBytes: Settings.CACHE_SIZE_UNLIMITED,
    );
  }

  DocumentReference get _cartDoc => 
    _firestore.collection('carts').doc(_userId);

  Stream<Cart> get cartStream {
    return _cartDoc.snapshots().map((snapshot) {
      if (!snapshot.exists) return Cart.empty(_userId);
      return Cart.fromFirestore(snapshot);
    });
  }

  Future<void> addToCart(Product product, {int quantity = 1}) async {
    await _firestore.runTransaction((transaction) async {
      DocumentSnapshot cartSnapshot = await transaction.get(_cartDoc);
      
      List<CartItem> items = [];
      if (cartSnapshot.exists) {
        final data = cartSnapshot.data() as Map<String, dynamic>;
        items = (data['items'] as List)
          .map((item) => CartItem.fromMap(item))
          .toList();
      }

      final existingIndex = items.indexWhere(
        (item) => item.productId == product.id
      );

      if (existingIndex >= 0) {
        items[existingIndex] = items[existingIndex].copyWith(
          quantity: items[existingIndex].quantity + quantity,
        );
      } else {
        items.add(CartItem(
          productId: product.id,
          name: product.name,
          price: product.price,
          quantity: quantity,
          image: product.images.isNotEmpty ? product.images[0] : null,
        ));
      }

      double totalAmount = items.fold(0, (sum, item) => 
        sum + (item.price * item.quantity));
      int itemCount = items.fold(0, (sum, item) => sum + item.quantity);

      transaction.set(_cartDoc, {
        'userId': _userId,
        'items': items.map((item) => item.toMap()).toList(),
        'totalAmount': totalAmount,
        'itemCount': itemCount,
        'updatedAt': FieldValue.serverTimestamp(),
      }, SetOptions(merge: true));
    });

    await FirebaseAnalytics.instance.logAddToCart(
      currency: 'NPR',
      value: product.price * quantity,
      items: [AnalyticsEventItem(
        itemName: product.name,
        itemId: product.id,
        quantity: quantity,
        price: product.price,
      )],
    );
  }

  Future<void> clearCart() async {
    await _cartDoc.update({
      'items': [],
      'totalAmount': 0,
      'itemCount': 0,
      'updatedAt': FieldValue.serverTimestamp(),
    });
  }
}
```

### 4.3 Order Management

```dart
class OrderService {
  final FirebaseFirestore _firestore = FirebaseFirestore.instance;

  Future<String> createOrder({
    required String userId,
    required List<CartItem> items,
    required double totalAmount,
    required Map<String, dynamic> deliveryAddress,
    required String paymentMethod,
  }) async {
    final orderRef = _firestore.collection('orders').doc();
    final orderId = orderRef.id;

    final orderData = {
      'id': orderId,
      'userId': userId,
      'items': items.map((item) => {
        'productId': item.productId,
        'name': item.name,
        'price': item.price,
        'quantity': item.quantity,
      }).toList(),
      'totalAmount': totalAmount,
      'deliveryAddress': deliveryAddress,
      'status': 'pending',
      'paymentMethod': paymentMethod,
      'createdAt': FieldValue.serverTimestamp(),
      'updatedAt': FieldValue.serverTimestamp(),
    };

    await orderRef.set(orderData);

    await orderRef.collection('order_status_updates').add({
      'status': 'pending',
      'message': 'Order placed successfully',
      'timestamp': FieldValue.serverTimestamp(),
      'updatedBy': 'system',
    });

    await FirebaseAnalytics.instance.logPurchase(
      currency: 'NPR',
      transactionId: orderId,
      value: totalAmount,
    );

    return orderId;
  }

  Stream<List<Order>> getUserOrders(String userId) {
    return _firestore
      .collection('orders')
      .where('userId', isEqualTo: userId)
      .orderBy('createdAt', descending: true)
      .snapshots()
      .map((snapshot) {
        return snapshot.docs.map((doc) => Order.fromFirestore(doc)).toList();
      });
  }
}
```


---

## 5. Firebase Realtime Database

### 5.1 When to Use vs Firestore

| Feature | Cloud Firestore | Realtime Database |
|---------|-----------------|-------------------|
| **Data Structure** | Documents and collections | JSON tree |
| **Queries** | Complex, compound queries | Simple filtering/sorting |
| **Offline** | Automatic offline persistence | Limited offline support |
| **Real-time** | Real-time listeners | Faster real-time sync |
| **Scalability** | Automatic scaling | Manual sharding needed |
| **Pricing** | Per operation | Per bandwidth/download |
| **Use Case** | Shopping cart, user profiles | Live order tracking |

### 5.2 Implementation for Live Order Tracking

```dart
import 'package:firebase_database/firebase_database.dart';

class LiveTrackingService {
  final FirebaseDatabase _database = FirebaseDatabase.instance;

  static void initialize() {
    FirebaseDatabase.instance.setPersistenceEnabled(true);
    FirebaseDatabase.instance.setPersistenceCacheSizeBytes(10000000);
  }

  Stream<DeliveryLocation?> trackOrder(String orderId) {
    final ref = _database.ref('order_tracking/$orderId');
    return ref.onValue.map((event) {
      if (event.snapshot.value == null) return null;
      final data = event.snapshot.value as Map<dynamic, dynamic>;
      return DeliveryLocation.fromMap(Map<String, dynamic>.from(data));
    });
  }

  Future<void> updateDeliveryLocation(
    String orderId,
    double latitude,
    double longitude,
  ) async {
    final ref = _database.ref('order_tracking/$orderId');
    await ref.set({
      'latitude': latitude,
      'longitude': longitude,
      'timestamp': ServerValue.timestamp,
    });
  }
}

class DeliveryLocation {
  final double latitude;
  final double longitude;
  final int timestamp;

  DeliveryLocation({
    required this.latitude,
    required this.longitude,
    required this.timestamp,
  });

  factory DeliveryLocation.fromMap(Map<String, dynamic> map) => DeliveryLocation(
    latitude: map['latitude']?.toDouble() ?? 0.0,
    longitude: map['longitude']?.toDouble() ?? 0.0,
    timestamp: map['timestamp'] ?? 0,
  );
}
```

### 5.3 Realtime Database Rules

```json
{
  "rules": {
    "order_tracking": {
      "$orderId": {
        ".write": "root.child('orders').child($orderId).child('deliveryPersonId').val() == auth.uid",
        ".read": "root.child('orders').child($orderId).child('userId').val() == auth.uid || 
                  root.child('orders').child($orderId).child('deliveryPersonId').val() == auth.uid"
      }
    }
  }
}
```

---

## 6. Cloud Messaging (FCM)

### 6.1 Push Notification Types

| Type | Trigger | Priority | Channel |
|------|---------|----------|---------|
| **Order Status** | Order state change | High | order_updates |
| **Delivery Alert** | Driver approaching | High | delivery_alerts |
| **Promotions** | New offers | Default | promotions |
| **Payment** | Payment success/failure | High | payment_updates |

### 6.2 Flutter FCM Integration

```dart
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';

class FCMService {
  final FirebaseMessaging _messaging = FirebaseMessaging.instance;
  final FlutterLocalNotificationsPlugin _localNotifications = 
    FlutterLocalNotificationsPlugin();

  Future<void> initialize() async {
    NotificationSettings settings = await _messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    const androidSettings = AndroidInitializationSettings('@drawable/ic_notification');
    const iosSettings = DarwinInitializationSettings();
    
    await _localNotifications.initialize(
      const InitializationSettings(android: androidSettings, iOS: iosSettings),
      onDidReceiveNotificationResponse: _handleNotificationTap,
    );

    await _createNotificationChannels();

    FirebaseMessaging.onMessage.listen(_handleForegroundMessage);
    FirebaseMessaging.onBackgroundMessage(_firebaseBackgroundHandler);
    FirebaseMessaging.onMessageOpenedApp.listen(_handleMessageOpenedApp);
  }

  Future<void> _createNotificationChannels() async {
    if (Platform.isAndroid) {
      final androidPlugin = _localNotifications
        .resolvePlatformSpecificImplementation<AndroidFlutterLocalNotificationsPlugin>();

      await androidPlugin?.createNotificationChannel(
        const AndroidNotificationChannel(
          'order_updates',
          'Order Updates',
          description: 'Notifications about your order status',
          importance: Importance.high,
        ),
      );
    }
  }

  Future<String?> getToken() async {
    return await _messaging.getToken();
  }

  void _handleForegroundMessage(RemoteMessage message) {
    final notification = message.notification;
    if (notification != null) {
      _showLocalNotification(
        id: message.hashCode,
        title: notification.title ?? 'Smart Dairy',
        body: notification.body ?? '',
        payload: message.data['screen'],
      );
    }
  }

  void _showLocalNotification({
    required int id,
    required String title,
    required String body,
    String? payload,
  }) async {
    final androidDetails = AndroidNotificationDetails(
      'order_updates',
      'Order Updates',
      importance: Importance.high,
      priority: Priority.high,
    );

    await _localNotifications.show(
      id,
      title,
      body,
      NotificationDetails(android: androidDetails),
      payload: payload,
    );
  }

  Future<void> saveTokenToDatabase(String userId) async {
    final token = await getToken();
    if (token != null) {
      await FirebaseFirestore.instance.collection('users').doc(userId).update({
        'fcmTokens': FieldValue.arrayUnion([token]),
      });
    }
  }
}

Future<void> _firebaseBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();
  print('Background message: ${message.messageId}');
}
```

### 6.3 Backend Notification Service (Python)

```python
import firebase_admin
from firebase_admin import credentials, messaging
from typing import List, Optional, Dict

class FCMService:
    _instance = None
    
    def __new__(cls):
        if cls._instance is None:
            cls._instance = super().__new__(cls)
            cls._instance._initialized = False
        return cls._instance
    
    def initialize(self, credentials_path: str):
        if not self._initialized:
            cred = credentials.Certificate(credentials_path)
            firebase_admin.initialize_app(cred)
            self._initialized = True
    
    def send_to_tokens(
        self,
        tokens: List[str],
        title: str,
        body: str,
        data: Optional[Dict[str, str]] = None,
    ) -> messaging.BatchResponse:
        message = messaging.MulticastMessage(
            notification=messaging.Notification(title=title, body=body),
            data=data or {},
            tokens=tokens,
        )
        return messaging.send_multicast(message)

    def send_to_topic(self, topic: str, title: str, body: str) -> str:
        message = messaging.Message(
            notification=messaging.Notification(title=title, body=body),
            topic=topic,
        )
        return messaging.send(message)


class SmartDairyNotifications:
    def __init__(self, fcm_service: FCMService):
        self.fcm = fcm_service
    
    def notify_order_status(self, tokens: List[str], order_id: str, status: str):
        status_messages = {
            'confirmed': 'Your order has been confirmed!',
            'preparing': 'We are preparing your order.',
            'out_for_delivery': 'Your order is out for delivery!',
            'delivered': 'Your order has been delivered.',
        }
        
        self.fcm.send_to_tokens(
            tokens=tokens,
            title='Order Update',
            body=status_messages.get(status, 'Order status updated'),
            data={'screen': '/order-details', 'orderId': order_id},
        )
    
    def send_promotional_offer(self, topic: str, title: str, body: str):
        self.fcm.send_to_topic(
            topic=topic,
            title=title,
            body=body,
        )
```

---

## 7. Firebase Storage

### 7.1 Storage Structure

```
users/{userId}/profile.jpg
products/{productId}/main.jpg
products/{productId}/gallery/{n}.jpg
orders/{orderId}/delivery_proof.jpg
```

### 7.2 Flutter Storage Implementation

```dart
import 'package:firebase_storage/firebase_storage.dart';
import 'dart:io';

class StorageService {
  final FirebaseStorage _storage = FirebaseStorage.instance;

  Future<String?> uploadProfilePicture(
    String userId,
    File imageFile,
  ) async {
    try {
      final ref = _storage.ref().child('users/$userId/profile.jpg');
      final uploadTask = ref.putFile(
        imageFile,
        SettableMetadata(contentType: 'image/jpeg'),
      );

      final snapshot = await uploadTask;
      final downloadUrl = await snapshot.ref.getDownloadURL();

      await FirebaseFirestore.instance.collection('users').doc(userId).update({
        'photoURL': downloadUrl,
      });

      return downloadUrl;
    } on FirebaseException catch (e) {
      print('Upload error: ${e.code}');
      return null;
    }
  }

  Future<String?> uploadProductImage(
    String productId,
    File imageFile, {
    bool isMainImage = false,
  }) async {
    try {
      String path = isMainImage
        ? 'products/$productId/main.jpg'
        : 'products/$productId/gallery/${DateTime.now().millisecondsSinceEpoch}.jpg';

      final ref = _storage.ref().child(path);
      final uploadTask = ref.putFile(imageFile);
      final snapshot = await uploadTask;
      return await snapshot.ref.getDownloadURL();
    } on FirebaseException catch (e) {
      print('Product image upload error: ${e.code}');
      return null;
    }
  }

  Future<void> deleteImage(String path) async {
    try {
      final ref = _storage.ref().child(path);
      await ref.delete();
    } on FirebaseException catch (e) {
      print('Delete error: ${e.code}');
    }
  }
}
```

### 7.3 Storage Security Rules

```javascript
rules_version = '2';
service firebase.storage {
  match /b/{bucket}/o {
    function isAuthenticated() {
      return request.auth != null;
    }
    
    function isOwner(userId) {
      return request.auth.uid == userId;
    }
    
    function isAdmin() {
      return request.auth.token.admin == true;
    }
    
    function isValidImage() {
      return request.resource.contentType.matches('image/.*') &&
             request.resource.size < 5 * 1024 * 1024;
    }

    match /users/{userId}/profile.jpg {
      allow read: if isAuthenticated();
      allow write: if isOwner(userId) && isValidImage();
    }

    match /products/{productId}/{allPaths=**} {
      allow read: if true;
      allow write: if isAdmin() && isValidImage();
    }

    match /{allPaths=**} {
      allow read, write: if false;
    }
  }
}
```

---

## 8. Firebase Analytics

### 8.1 Custom Events for Smart Dairy

```dart
import 'package:firebase_analytics/firebase_analytics.dart';

class AnalyticsService {
  static final FirebaseAnalytics _analytics = FirebaseAnalytics.instance;

  static Future<void> initialize() async {
    await _analytics.setAnalyticsCollectionEnabled(true);
  }

  static Future<void> logScreenView(String screenName) async {
    await _analytics.logScreenView(screenName: screenName);
  }

  static Future<void> logProductView(
    String productId,
    String productName,
    String category, {
    double? price,
  }) async {
    await _analytics.logViewItem(
      currency: 'NPR',
      value: price,
      items: [AnalyticsEventItem(
        itemId: productId,
        itemName: productName,
        itemCategory: category,
        price: price,
      )],
    );
  }

  static Future<void> logAddToCart(
    String productId,
    String productName,
    double price,
    int quantity,
  ) async {
    await _analytics.logAddToCart(
      currency: 'NPR',
      value: price * quantity,
      items: [AnalyticsEventItem(
        itemId: productId,
        itemName: productName,
        price: price,
        quantity: quantity,
      )],
    );
  }

  static Future<void> logPurchase(
    String orderId,
    double value,
    List<CartItem> items,
  ) async {
    await _analytics.logPurchase(
      currency: 'NPR',
      transactionId: orderId,
      value: value,
      items: items.map((item) => AnalyticsEventItem(
        itemId: item.productId,
        itemName: item.name,
        price: item.price,
        quantity: item.quantity,
      )).toList(),
    );
  }

  static Future<void> logSearch(String searchTerm) async {
    await _analytics.logSearch(searchTerm: searchTerm);
  }
}
```

---

## 9. Crashlytics

```dart
import 'package:firebase_crashlytics/firebase_crashlytics.dart';
import 'package:flutter/foundation.dart';

class CrashlyticsService {
  static final FirebaseCrashlytics _crashlytics = FirebaseCrashlytics.instance;

  static Future<void> initialize() async {
    FlutterError.onError = (errorDetails) {
      _crashlytics.recordFlutterFatalError(errorDetails);
    };

    PlatformDispatcher.instance.onError = (error, stack) {
      _crashlytics.recordError(error, stack, fatal: true);
      return true;
    };

    await _crashlytics.setCrashlyticsCollectionEnabled(true);
  }

  static Future<void> setUserIdentifier(String userId) async {
    await _crashlytics.setUserIdentifier(userId);
  }

  static Future<void> log(String message) async {
    await _crashlytics.log(message);
  }

  static Future<void> recordError(
    dynamic exception,
    StackTrace? stack, {
    bool fatal = false,
  }) async {
    await _crashlytics.recordError(exception, stack, fatal: fatal);
  }
}
```

---

## 10. Remote Config

### 10.1 Configuration Parameters

| Parameter | Default | Description |
|-----------|---------|-------------|
| `min_app_version` | "1.0.0" | Minimum required app version |
| `feature_promotions_enabled` | true | Enable promotions feature |
| `free_delivery_threshold` | 500 | Minimum order for free delivery |
| `delivery_charge` | 50 | Standard delivery charge |
| `maintenance_mode` | false | App maintenance mode |

### 10.2 Flutter Implementation

```dart
import 'package:firebase_remote_config/firebase_remote_config.dart';

class RemoteConfigService {
  static final FirebaseRemoteConfig _remoteConfig = FirebaseRemoteConfig.instance;

  static Future<void> initialize() async {
    await _remoteConfig.setConfigSettings(RemoteConfigSettings(
      fetchTimeout: const Duration(minutes: 1),
      minimumFetchInterval: const Duration(hours: 1),
    ));

    await _remoteConfig.setDefaults({
      'min_app_version': '1.0.0',
      'feature_promotions_enabled': true,
      'free_delivery_threshold': 500,
      'delivery_charge': 50,
      'maintenance_mode': false,
    });

    await fetchAndActivate();
  }

  static Future<bool> fetchAndActivate() async {
    try {
      return await _remoteConfig.fetchAndActivate();
    } catch (e) {
      return false;
    }
  }

  static String get minAppVersion => 
    _remoteConfig.getString('min_app_version');
  
  static bool get featurePromotionsEnabled => 
    _remoteConfig.getBool('feature_promotions_enabled');
  
  static double get freeDeliveryThreshold => 
    _remoteConfig.getDouble('free_delivery_threshold');
  
  static double get deliveryCharge => 
    _remoteConfig.getDouble('delivery_charge');
  
  static bool get maintenanceMode => 
    _remoteConfig.getBool('maintenance_mode');
}
```


---

## 11. Implementation - Flutter Integration

### 11.1 Dependencies (pubspec.yaml)

```yaml
dependencies:
  flutter:
    sdk: flutter

  firebase_core: ^2.24.2
  firebase_auth: ^4.16.0
  cloud_firestore: ^4.14.0
  firebase_database: ^10.4.0
  firebase_storage: ^11.6.0
  firebase_messaging: ^14.7.10
  firebase_analytics: ^10.8.0
  firebase_crashlytics: ^3.4.9
  firebase_remote_config: ^4.3.8
  flutter_local_notifications: ^16.3.2
  image_picker: ^1.0.7
  provider: ^6.1.1
  shared_preferences: ^2.2.2
```

### 11.2 Main App Setup

```dart
import 'package:flutter/material.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_auth/firebase_auth.dart';
import 'package:firebase_analytics/firebase_analytics.dart';
import 'package:provider/provider.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await Firebase.initializeApp(options: DefaultFirebaseOptions.currentPlatform);
  
  await CrashlyticsService.initialize();
  await RemoteConfigService.initialize();
  await FirebaseAnalytics.instance.setAnalyticsCollectionEnabled(true);
  
  final fcmService = FCMService();
  await fcmService.initialize();
  
  await FirebaseFirestore.instance.enablePersistence();
  
  runApp(const SmartDairyApp());
}

class SmartDairyApp extends StatelessWidget {
  const SmartDairyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
        ChangeNotifierProvider(create: (_) => CartProvider()),
      ],
      child: MaterialApp(
        title: 'Smart Dairy',
        navigatorObservers: [
          FirebaseAnalyticsObserver(analytics: FirebaseAnalytics.instance),
        ],
        theme: ThemeData(primarySwatch: Colors.blue),
        home: const AuthWrapper(),
      ),
    );
  }
}

class AuthWrapper extends StatelessWidget {
  const AuthWrapper({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return StreamBuilder<User?>(
      stream: FirebaseAuth.instance.authStateChanges(),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const SplashScreen();
        }
        
        if (snapshot.hasData) {
          final user = snapshot.data!;
          FirebaseAnalytics.instance.setUserId(id: user.uid);
          CrashlyticsService.setUserIdentifier(user.uid);
          
          final fcmService = FCMService();
          fcmService.saveTokenToDatabase(user.uid);
          
          return const HomeScreen();
        }
        
        return const LoginScreen();
      },
    );
  }
}
```

---

## 12. Security Rules

### 12.1 Firestore Security Rules

```javascript
rules_version = '2';
service cloud.firestore {
  match /databases/{database}/documents {
    
    function isAuthenticated() {
      return request.auth != null;
    }
    
    function isOwner(userId) {
      return request.auth.uid == userId;
    }
    
    function isAdmin() {
      return request.auth.token.admin == true;
    }

    match /users/{userId} {
      allow read: if isAuthenticated() && (isOwner(userId) || isAdmin());
      allow create: if isAuthenticated() && isOwner(userId);
      allow update: if isAuthenticated() && isOwner(userId);
      allow delete: if isAdmin();
    }

    match /carts/{userId} {
      allow read, write: if isAuthenticated() && isOwner(userId);
    }

    match /products/{productId} {
      allow read: if true;
      allow write: if isAdmin();
    }

    match /categories/{categoryId} {
      allow read: if true;
      allow write: if isAdmin();
    }

    match /orders/{orderId} {
      allow read: if isAuthenticated() && (
        resource.data.userId == request.auth.uid || isAdmin()
      );
      
      allow create: if isAuthenticated() && 
        request.resource.data.userId == request.auth.uid;
      
      allow update: if isAuthenticated() && (
        (resource.data.userId == request.auth.uid &&
         resource.data.status == 'pending') || isAdmin()
      );
    }

    match /promotions/{promoId} {
      allow read: if true;
      allow write: if isAdmin();
    }

    match /{document=**} {
      allow read, write: if false;
    }
  }
}
```

### 12.2 Testing Security Rules

```javascript
// firestore.rules.test.js
const { initializeTestEnvironment, assertFails, assertSucceeds } = 
  require('@firebase/rules-unit-testing');

let testEnv;

beforeAll(async () => {
  testEnv = await initializeTestEnvironment({
    projectId: 'smart-dairy-ltd',
  });
});

describe('Firestore Security Rules', () => {
  it('should allow users to read their own document', async () => {
    const userId = 'user123';
    const context = testEnv.authenticatedContext(userId);
    await assertSucceeds(
      context.firestore().doc(`users/${userId}`).get()
    );
  });

  it('should not allow users to read other user documents', async () => {
    const context = testEnv.authenticatedContext('user1');
    await assertFails(
      context.firestore().doc('users/user2').get()
    );
  });
});
```

---

## 13. Backend Integration - Python Admin SDK

### 13.1 Setup and Initialization

```python
# firebase_admin_config.py
import firebase_admin
from firebase_admin import credentials, auth, firestore, storage, messaging
import os

def initialize_firebase_admin():
    cred_path = os.getenv('FIREBASE_SERVICE_ACCOUNT_PATH', 'service-account.json')
    
    if not firebase_admin._apps:
        cred = credentials.Certificate(cred_path)
        firebase_admin.initialize_app(cred, {
            'storageBucket': 'smart-dairy-ltd.appspot.com'
        })

def get_firestore():
    return firestore.client()

def get_auth():
    return auth
```

### 13.2 Token Verification Middleware

```python
# auth_middleware.py
from firebase_admin import auth
from functools import wraps
from flask import request, jsonify, g

def verify_firebase_token(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        auth_header = request.headers.get('Authorization')
        
        if not auth_header:
            return jsonify({'error': 'Authorization header missing'}), 401
        
        try:
            token = auth_header.split(' ')[1] if ' ' in auth_header else auth_header
            decoded_token = auth.verify_id_token(token)
            
            g.user = {
                'uid': decoded_token['uid'],
                'email': decoded_token.get('email'),
                'phone_number': decoded_token.get('phone_number'),
                'is_admin': decoded_token.get('admin', False),
            }
            
            return f(*args, **kwargs)
            
        except auth.ExpiredIdTokenError:
            return jsonify({'error': 'Token expired'}), 401
        except auth.InvalidIdTokenError:
            return jsonify({'error': 'Invalid token'}), 401
        except Exception as e:
            return jsonify({'error': 'Authentication failed'}), 401
    
    return decorated_function

def require_admin(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        if not hasattr(g, 'user') or not g.user.get('is_admin'):
            return jsonify({'error': 'Admin access required'}), 403
        return f(*args, **kwargs)
    return decorated_function
```

### 13.3 Admin Operations

```python
# admin_operations.py
from firebase_admin import auth, firestore
from datetime import datetime

class FirebaseAdminOperations:
    def __init__(self):
        self.db = firestore.client()
    
    def create_user(self, email: str, password: str, 
                    display_name: str = None, admin: bool = False):
        user = auth.create_user(
            email=email,
            password=password,
            display_name=display_name,
        )
        
        if admin:
            auth.set_custom_user_claims(user.uid, {'admin': True})
        
        user_doc = {
            'uid': user.uid,
            'email': email,
            'fullName': display_name,
            'isAdmin': admin,
            'createdAt': firestore.SERVER_TIMESTAMP,
        }
        self.db.collection('users').document(user.uid).set(user_doc)
        return {'uid': user.uid, 'email': email}
    
    def set_admin_role(self, uid: str, is_admin: bool = True):
        auth.set_custom_user_claims(uid, {'admin': is_admin})
        self.db.collection('users').document(uid).update({
            'isAdmin': is_admin,
        })
    
    def update_order_status(self, order_id: str, status: str, 
                           message: str = None):
        order_ref = self.db.collection('orders').document(order_id)
        
        order_ref.update({
            'status': status,
            'updatedAt': firestore.SERVER_TIMESTAMP,
        })
        
        order_ref.collection('order_status_updates').add({
            'status': status,
            'message': message or f'Order status updated to {status}',
            'timestamp': firestore.SERVER_TIMESTAMP,
        })
    
    def disable_user(self, uid: str):
        auth.update_user(uid, disabled=True)
        self.db.collection('users').document(uid).update({
            'disabled': True,
        })
```

### 13.4 API Routes Example

```python
# api.py
from flask import Flask, request, jsonify, g
from firebase_admin_config import initialize_firebase_admin
from auth_middleware import verify_firebase_token, require_admin
from admin_operations import FirebaseAdminOperations

app = Flask(__name__)
initialize_firebase_admin()
admin_ops = FirebaseAdminOperations()

@app.route('/api/user/profile', methods=['GET'])
@verify_firebase_token
def get_user_profile():
    user = g.user
    db = firestore.client()
    user_doc = db.collection('users').document(user['uid']).get()
    
    if user_doc.exists:
        return jsonify({'success': True, 'data': user_doc.to_dict()})
    return jsonify({'success': False, 'error': 'User not found'}), 404

@app.route('/api/orders', methods=['GET'])
@verify_firebase_token
def get_user_orders():
    user_id = g.user['uid']
    db = firestore.client()
    orders = db.collection('orders')\
        .where('userId', '==', user_id)\
        .order_by('createdAt', direction=firestore.Query.DESCENDING)\
        .stream()
    
    return jsonify({
        'success': True,
        'data': [order.to_dict() for order in orders]
    })

@app.route('/api/admin/orders/<order_id>/status', methods=['PUT'])
@verify_firebase_token
@require_admin
def admin_update_order_status(order_id):
    data = request.json
    admin_ops.update_order_status(
        order_id, 
        data.get('status'), 
        data.get('message'),
    )
    return jsonify({'success': True})

if __name__ == '__main__':
    app.run(debug=True)
```

---

## 14. Appendices

### 14.1 Complete Code Examples

#### Firebase Configuration File

```dart
// lib/firebase_options.dart
import 'package:firebase_core/firebase_core.dart';
import 'package:flutter/foundation.dart';

class DefaultFirebaseOptions {
  static FirebaseOptions get currentPlatform {
    switch (defaultTargetPlatform) {
      case TargetPlatform.android:
        return android;
      case TargetPlatform.iOS:
        return ios;
      default:
        throw UnsupportedError('Platform not supported');
    }
  }

  static const FirebaseOptions android = FirebaseOptions(
    apiKey: 'YOUR_API_KEY',
    appId: 'YOUR_ANDROID_APP_ID',
    messagingSenderId: 'YOUR_SENDER_ID',
    projectId: 'smart-dairy-ltd',
    storageBucket: 'smart-dairy-ltd.appspot.com',
  );

  static const FirebaseOptions ios = FirebaseOptions(
    apiKey: 'YOUR_API_KEY',
    appId: 'YOUR_IOS_APP_ID',
    messagingSenderId: 'YOUR_SENDER_ID',
    projectId: 'smart-dairy-ltd',
    storageBucket: 'smart-dairy-ltd.appspot.com',
    iosClientId: 'YOUR_IOS_CLIENT_ID',
    iosBundleId: 'com.smartdairy.mobile',
  );
}
```

### 14.2 Firebase Pricing for Smart Dairy

#### Spark Plan (Free Tier)

| Service | Free Tier | Smart Dairy Usage |
|---------|-----------|-------------------|
| **Authentication** | 10k users/month | Sufficient for launch |
| **Firestore** | 50k reads, 20k writes/day | Monitor closely |
| **Storage** | 1 GB total | Sufficient for images |
| **Bandwidth** | 10 GB/month | May need upgrade |
| **Analytics** | Unlimited | Always free |
| **Crashlytics** | Unlimited | Always free |

#### Blaze Plan (Pay-as-you-go)

Estimated monthly cost for 10,000 active users: **$18-25 USD**

### 14.3 Best Practices

#### Security
1. Never expose service account keys in client code
2. Validate all data on the server before writing to Firestore
3. Use security rules to enforce data access patterns
4. Implement rate limiting for sensitive operations

#### Performance
1. Use pagination for large collections
2. Implement proper indexing for queries
3. Cache frequently accessed data
4. Use transactions for atomic operations

#### Reliability
1. Handle offline scenarios gracefully
2. Implement retry logic for failed operations
3. Monitor error rates and latency
4. Set up alerts for critical failures

### 14.4 Troubleshooting Guide

| Issue | Solution |
|-------|----------|
| Auth token expired | Implement automatic token refresh |
| Firestore offline | Enable persistence, handle connectivity |
| FCM not receiving | Check permissions, token registration |
| Storage upload fails | Validate file size, check rules |

### 14.5 Integration Checklist

- [ ] Firebase project created
- [ ] Android app registered
- [ ] iOS app registered
- [ ] Web app registered
- [ ] Authentication methods configured
- [ ] Firestore database created
- [ ] Storage bucket configured
- [ ] FCM enabled
- [ ] Analytics enabled
- [ ] Crashlytics enabled
- [ ] Security rules deployed
- [ ] Indexes created
- [ ] Backend SDK integrated
- [ ] Testing completed

---

## Document Control

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Integration Engineer | | January 31, 2026 |
| Reviewer | CTO | | |
| Approver | Tech Lead | | |

---

*End of Document E-010*
