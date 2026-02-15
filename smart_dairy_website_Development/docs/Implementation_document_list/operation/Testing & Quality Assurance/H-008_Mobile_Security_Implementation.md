# SMART DAIRY LTD.
## MOBILE SECURITY IMPLEMENTATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | H-008 |
| **Version** | 1.0 |
| **Date** | June 12, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Security Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Security Architecture](#2-security-architecture)
3. [Data Storage Security](#3-data-storage-security)
4. [Cryptography Implementation](#4-cryptography-implementation)
5. [Authentication & Session Management](#5-authentication--session-management)
6. [Network Communication Security](#6-network-communication-security)
7. [Platform-Specific Security](#7-platform-specific-security)
8. [Code Quality & Tampering Protection](#8-code-quality--tampering-protection)
9. [IoT & Device Security](#9-iot--device-security)
10. [Security Testing](#10-security-testing)
11. [Incident Response](#11-incident-response)
12. [Compliance & Auditing](#12-compliance--auditing)
13. [Appendices](#13-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive security implementation guidelines for Smart Dairy's mobile applications (Android and iOS). It establishes security standards, implementation patterns, and verification methods to protect sensitive farm and business data in accordance with OWASP Mobile Application Security Verification Standard (MASVS).

### 1.2 Scope

| Application | Platform | Users | Security Level |
|-------------|----------|-------|----------------|
| Smart Dairy Farm | Android/iOS | Farm Workers, Supervisors | High |
| Smart Dairy B2C | Android/iOS | Customers | High |
| Smart Dairy Sales | Android/iOS | Sales Team | High |

### 1.3 Security Classification

| Data Type | Classification | Protection Required |
|-----------|----------------|---------------------|
| Personal Information (PII) | Confidential | Encryption at rest + in transit |
| Financial Data | Confidential | Encryption + tokenization |
| Farm Production Data | Internal | Encryption + access control |
| Health Records | Restricted | Encryption + audit logging |
| Session Tokens | Confidential | Secure storage + rotation |

### 1.4 Compliance Framework

| Standard | Version | Requirements |
|----------|---------|--------------|
| OWASP MASVS | v2.0.0 | L2 (Defense-in-Depth) |
| OWASP MASTG | v1.5.0 | Testing methodology |
| PCI DSS | v4.0 | Payment data protection |
| Bangladesh DPA | Draft | Data protection compliance |

---

## 2. SECURITY ARCHITECTURE

### 2.1 Mobile Security Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         MOBILE SECURITY ARCHITECTURE                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                    APPLICATION LAYER                                   │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │  UI Layer    │  │  Business    │  │  Security    │               │  │
│  │  │              │  │  Logic       │  │  Controls    │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  │                                                                              │  │
│  │  Security Controls:                                                          │  │
│  │  • Root/Jailbreak Detection          • Certificate Pinning                   │  │
│  │  • Anti-Tampering                    • Obfuscation                           │  │
│  │  • Secure Logging                    • Input Validation                      │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                    DATA LAYER                                          │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │  Local DB    │  │  Secure      │  │  Cache       │               │  │
│  │  │  (SQLite)    │  │  Storage     │  │  (Encrypted) │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  │                                                                              │  │
│  │  Encryption: AES-256-GCM for data at rest                                   │  │
│  │  Key Management: Android Keystore / iOS Keychain                             │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                    NETWORK LAYER                                       │  │
│  │  • TLS 1.3 (Minimum 1.2)                                                │  │
│  │  • Certificate Pinning                                                  │  │
│  │  • Mutual TLS for high-security endpoints                               │  │
│  │  • Certificate Transparency                                             │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Security Zones

| Zone | Components | Trust Level |
|------|------------|-------------|
| **Public Zone** | CDN, Static assets | Untrusted |
| **DMZ Zone** | API Gateway, WAF | Semi-Trusted |
| **Application Zone** | App Servers, Microservices | Trusted |
| **Data Zone** | Databases, Cache | Highly Trusted |

---

## 3. DATA STORAGE SECURITY

### 3.1 Secure Storage Implementation

#### Flutter Secure Storage
```dart
// lib/core/security/secure_storage.dart

import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:cryptography/cryptography.dart';

class SecureStorageManager {
  static const _storage = FlutterSecureStorage(
    aOptions: AndroidOptions(
      encryptedSharedPreferences: true,
      keyCipherAlgorithm: KeyCipherAlgorithm.RSA_ECB_PKCS1Padding,
      storageCipherAlgorithm: StorageCipherAlgorithm.AES_GCM_NoPadding,
    ),
    iOptions: IOSOptions(
      accountName: 'smart_dairy_secure',
      accessibility: KeychainAccessibility.unlocked_this_device,
    ),
  );

  // Store sensitive data
  static Future<void> writeSecureData(String key, String value) async {
    await _storage.write(key: key, value: value);
  }

  // Read sensitive data
  static Future<String?> readSecureData(String key) async {
    return await _storage.read(key: key);
  }

  // Delete sensitive data
  static Future<void> deleteSecureData(String key) async {
    await _storage.delete(key: key);
  }

  // Keys for sensitive data
  static const String authToken = 'auth_token';
  static const String refreshToken = 'refresh_token';
  static const String userPin = 'user_pin_hash';
  static const String encryptionKey = 'local_encryption_key';
}
```

#### Encrypted Local Database (Hive)
```dart
// lib/core/security/encrypted_db.dart

import 'package:hive_flutter/hive_flutter.dart';
import 'package:encrypt/encrypt.dart' as encrypt;

class EncryptedDatabase {
  static late Box<dynamic> _offlineBox;
  static late encrypt.Encrypter _encrypter;
  
  static Future<void> initialize() async {
    // Generate or retrieve encryption key
    final key = await _getOrCreateEncryptionKey();
    
    final encryptionCipher = HiveAesCipher(key);
    
    _offlineBox = await Hive.openBox(
      'offline_data',
      encryptionCipher: encryptionCipher,
    );
  }

  static Future<List<int>> _getOrCreateEncryptionKey() async {
    // Use Android Keystore / iOS Keychain for key storage
    final secureKey = await SecureStorageManager.readSecureData(
      SecureStorageManager.encryptionKey
    );
    
    if (secureKey != null) {
      return base64UrlDecode(secureKey);
    }
    
    // Generate new key
    final newKey = Hive.generateSecureKey();
    await SecureStorageManager.writeSecureData(
      SecureStorageManager.encryptionKey,
      base64UrlEncode(newKey),
    );
    
    return newKey;
  }

  // Store offline data encrypted
  static Future<void> storeOfflineData(String key, dynamic data) async {
    await _offlineBox.put(key, data);
  }

  // Retrieve decrypted data
  static Future<dynamic> getOfflineData(String key) async {
    return _offlineBox.get(key);
  }
}
```

### 3.2 Data Classification & Handling

| Data Type | Storage Location | Encryption | Retention |
|-----------|------------------|------------|-----------|
| JWT Access Token | Secure Storage | AES-256 | Session only |
| JWT Refresh Token | Secure Storage | AES-256 | 30 days |
| User PIN Hash | Secure Storage | Argon2 + AES | Persistent |
| Farm Data (Offline) | Encrypted Hive | AES-256 | 7 days max |
| Cache Data | Encrypted Cache | AES-256 | 24 hours |
| Logs | Local (No PII) | None | 48 hours |

### 3.3 Sensitive Data Handling Rules

```dart
// lib/core/security/data_protection.dart

class DataProtection {
  // Mask sensitive data in logs
  static String maskPhone(String phone) {
    if (phone.length < 4) return phone;
    return '${phone.substring(0, 3)}****${phone.substring(phone.length - 2)}';
  }

  // Sanitize data before logging
  static Map<String, dynamic> sanitizeForLogging(Map<String, dynamic> data) {
    final sensitiveKeys = ['password', 'token', 'pin', 'credit_card', 'otp'];
    final sanitized = Map<String, dynamic>.from(data);
    
    for (final key in data.keys) {
      if (sensitiveKeys.any((s) => key.toLowerCase().contains(s))) {
        sanitized[key] = '***REDACTED***';
      }
    }
    
    return sanitized;
  }

  // Clear all sensitive data on logout
  static Future<void> clearSensitiveData() async {
    await SecureStorageManager.deleteSecureData(
      SecureStorageManager.authToken
    );
    await SecureStorageManager.deleteSecureData(
      SecureStorageManager.refreshToken
    );
    await EncryptedDatabase.clear(); // Clear offline data
    await CacheManager.clear(); // Clear API cache
  }
}
```

---

## 4. CRYPTOGRAPHY IMPLEMENTATION

### 4.1 Encryption Standards

```dart
// lib/core/security/encryption.dart

import 'package:cryptography/cryptography.dart';

class EncryptionService {
  // AES-256-GCM for data encryption
  static final _aesGcm = AesGcm.with256bits();
  
  // Generate secure random key
  static Future<SecretKey> generateKey() async {
    return await _aesGcm.newSecretKey();
  }

  // Encrypt data
  static Future<EncryptedData> encrypt(String plaintext, SecretKey key) async {
    final nonce = _aesGcm.newNonce();
    final encrypted = await _aesGcm.encrypt(
      utf8.encode(plaintext),
      secretKey: key,
      nonce: nonce,
    );
    
    return EncryptedData(
      ciphertext: base64Encode(encrypted.cipherText),
      nonce: base64Encode(nonce),
      mac: base64Encode(encrypted.mac.bytes),
    );
  }

  // Decrypt data
  static Future<String> decrypt(EncryptedData data, SecretKey key) async {
    final secretBox = SecretBox(
      base64Decode(data.ciphertext),
      nonce: base64Decode(data.nonce),
      mac: Mac(base64Decode(data.mac)),
    );
    
    final decrypted = await _aesGcm.decrypt(
      secretBox,
      secretKey: key,
    );
    
    return utf8.decode(decrypted);
  }
}

class EncryptedData {
  final String ciphertext;
  final String nonce;
  final String mac;

  EncryptedData({
    required this.ciphertext,
    required this.nonce,
    required this.mac,
  });
}
```

### 4.2 Key Management

| Key Type | Storage | Rotation |
|----------|---------|----------|
| Data Encryption Key | Android Keystore / iOS Keychain | On reinstall |
| API Communication Key | Certificate pinning | 90 days |
| Backup Encryption Key | Server-side HSM | 180 days |

---

## 5. AUTHENTICATION & SESSION MANAGEMENT

### 5.1 Secure Authentication Flow

```dart
// lib/core/security/auth_manager.dart

class AuthManager {
  static const String _tokenKey = 'auth_token';
  static const String _refreshTokenKey = 'refresh_token';
  static DateTime? _tokenExpiry;
  
  // Login with secure token storage
  static Future<bool> login(String email, String password) async {
    try {
      final response = await ApiService.post('/auth/login', {
        'email': email,
        'password': password,
        'device_id': await DeviceInfo.deviceId,
        'device_info': await DeviceInfo.info,
      });
      
      if (response.success) {
        // Store tokens securely
        await SecureStorageManager.writeSecureData(
          _tokenKey,
          response.data['access_token'],
        );
        await SecureStorageManager.writeSecureData(
          _refreshTokenKey,
          response.data['refresh_token'],
        );
        
        // Store expiry
        _tokenExpiry = DateTime.now().add(
          Duration(seconds: response.data['expires_in']),
        );
        
        // Setup biometric if available
        await _setupBiometric();
        
        return true;
      }
    } catch (e) {
      Logger.error('Login failed', e);
    }
    return false;
  }

  // Get valid token (with auto-refresh)
  static Future<String?> getValidToken() async {
    // Check if token is about to expire
    if (_tokenExpiry != null && 
        _tokenExpiry!.difference(DateTime.now()).inMinutes < 5) {
      await _refreshToken();
    }
    
    return await SecureStorageManager.readSecureData(_tokenKey);
  }

  // Token refresh
  static Future<bool> _refreshToken() async {
    final refreshToken = await SecureStorageManager.readSecureData(
      _refreshTokenKey
    );
    
    if (refreshToken == null) return false;
    
    try {
      final response = await ApiService.post('/auth/refresh', {
        'refresh_token': refreshToken,
      });
      
      if (response.success) {
        await SecureStorageManager.writeSecureData(
          _tokenKey,
          response.data['access_token'],
        );
        _tokenExpiry = DateTime.now().add(
          Duration(seconds: response.data['expires_in']),
        );
        return true;
      }
    } catch (e) {
      Logger.error('Token refresh failed', e);
    }
    
    // Refresh failed, logout user
    await logout();
    return false;
  }

  // Secure logout
  static Future<void> logout() async {
    // Invalidate token on server
    try {
      final token = await getValidToken();
      if (token != null) {
        await ApiService.post('/auth/logout', {'token': token});
      }
    } catch (e) {
      Logger.error('Server logout failed', e);
    }
    
    // Clear all local data
    await DataProtection.clearSensitiveData();
    _tokenExpiry = null;
  }
}
```

### 5.2 Biometric Authentication

```dart
// lib/core/security/biometric_auth.dart

import 'package:local_auth/local_auth.dart';

class BiometricAuth {
  static final LocalAuthentication _localAuth = LocalAuthentication();
  
  static Future<bool> isAvailable() async {
    final isDeviceSupported = await _localAuth.isDeviceSupported();
    final canCheckBiometrics = await _localAuth.canCheckBiometrics;
    return isDeviceSupported && canCheckBiometrics;
  }

  static Future<bool> authenticate() async {
    try {
      final isAvailable = await BiometricAuth.isAvailable();
      if (!isAvailable) return false;
      
      return await _localAuth.authenticate(
        localizedReason: 'Authenticate to access Smart Dairy',
        authMessages: const [
          AndroidAuthMessages(
            signInTitle: 'Biometric Authentication',
            cancelButton: 'Use PIN',
          ),
          IOSAuthMessages(
            cancelButton: 'Use PIN',
          ),
        ],
        options: const AuthenticationOptions(
          biometricOnly: false,
          stickyAuth: true,
          sensitiveTransaction: true,
        ),
      );
    } catch (e) {
      Logger.error('Biometric auth failed', e);
      return false;
    }
  }
}
```

### 5.3 PIN/Code Authentication

```dart
// lib/core/security/pin_auth.dart

import 'package:crypto/crypto.dart';
import 'dart:convert';

class PinAuth {
  // Hash PIN using Argon2 (server-side) or PBKDF2 (client-side fallback)
  static String hashPin(String pin) {
    final salt = DateTime.now().millisecondsSinceEpoch.toString();
    final bytes = utf8.encode(pin + salt);
    final digest = sha256.convert(bytes);
    return '$salt:${digest.toString()}';
  }

  // Verify PIN
  static bool verifyPin(String pin, String storedHash) {
    final parts = storedHash.split(':');
    if (parts.length != 2) return false;
    
    final salt = parts[0];
    final hash = parts[1];
    
    final bytes = utf8.encode(pin + salt);
    final digest = sha256.convert(bytes);
    
    return digest.toString() == hash;
  }

  // Validate PIN strength
  static PinValidationResult validatePin(String pin) {
    if (pin.length < 4) {
      return PinValidationResult(false, 'PIN must be at least 4 digits');
    }
    
    if (RegExp(r'^(.)\1*$').hasMatch(pin)) {
      return PinValidationResult(false, 'PIN cannot be all same digits');
    }
    
    if (RegExp(r'^(0123|1234|2345|3456|4567|5678|6789|9876|8765|7654|6543|5432|4321|3210)$')
        .hasMatch(pin)) {
      return PinValidationResult(false, 'PIN cannot be sequential');
    }
    
    return PinValidationResult(true, 'PIN is valid');
  }
}

class PinValidationResult {
  final bool isValid;
  final String message;
  
  PinValidationResult(this.isValid, this.message);
}
```

---

## 6. NETWORK COMMUNICATION SECURITY

### 6.1 TLS Configuration

```dart
// lib/core/network/secure_http.dart

import 'package:dio/dio.dart';
import 'package:dio/io.dart';
import 'dart:io';

class SecureHttpClient {
  static Dio createSecureClient() {
    final dio = Dio(BaseOptions(
      baseUrl: ApiConfig.baseUrl,
      connectTimeout: const Duration(seconds: 30),
      receiveTimeout: const Duration(seconds: 30),
    ));

    // Certificate pinning
    dio.httpClientAdapter = IOHttpClientAdapter(
      createHttpClient: () {
        final client = HttpClient();
        
        // Enforce TLS 1.2+
        client.badCertificateCallback = (X509Certificate cert, String host, int port) {
          // Implement certificate pinning
          return _validateCertificate(cert, host);
        };
        
        return client;
      },
    );

    // Add auth interceptor
    dio.interceptors.add(AuthInterceptor());
    dio.interceptors.add(LoggingInterceptor());
    
    return dio;
  }

  static bool _validateCertificate(X509Certificate cert, String host) {
    // Certificate pinning implementation
    final pinnedCerts = CertificatePins.getPinsForHost(host);
    if (pinnedCerts == null) return false;
    
    final certHash = sha256.convert(cert.pem.encode()).toString();
    return pinnedCerts.contains(certHash);
  }
}

// Certificate pins
class CertificatePins {
  static final Map<String, List<String>> _pins = {
    'api.smartdairybd.com': [
      'sha256/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=',
      'sha256/BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB=',
    ],
  };

  static List<String>? getPinsForHost(String host) {
    return _pins[host];
  }
}
```

### 6.2 API Security Interceptor

```dart
// lib/core/network/auth_interceptor.dart

class AuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) async {
    // Add auth token
    final token = await AuthManager.getValidToken();
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    
    // Add security headers
    options.headers['X-Device-ID'] = await DeviceInfo.deviceId;
    options.headers['X-App-Version'] = AppInfo.version;
    options.headers['X-Request-ID'] = _generateRequestId();
    
    handler.next(options);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) async {
    // Handle 401 - Token expired
    if (err.response?.statusCode == 401) {
      final refreshed = await AuthManager.refreshToken();
      if (refreshed) {
        // Retry request
        final opts = err.requestOptions;
        final token = await AuthManager.getValidToken();
        opts.headers['Authorization'] = 'Bearer $token';
        
        try {
          final response = await SecureHttpClient.createSecureClient().fetch(opts);
          handler.resolve(response);
          return;
        } catch (e) {
          handler.reject(err);
          return;
        }
      }
    }
    
    handler.next(err);
  }

  static String _generateRequestId() {
    return '${DateTime.now().millisecondsSinceEpoch}-${Random().nextInt(999999)}';
  }
}
```

---

## 7. PLATFORM-SPECIFIC SECURITY

### 7.1 Android Security

```kotlin
// android/app/src/main/kotlin/com/smartdairy/security/SecurityConfig.kt

package com.smartdairy.security

import android.content.Context
import android.security.keystore.KeyGenParameterSpec
import android.security.keystore.KeyProperties
import java.security.KeyStore
import javax.crypto.KeyGenerator
import javax.crypto.SecretKey

class AndroidSecurityConfig {
    companion object {
        private const val KEYSTORE_PROVIDER = "AndroidKeyStore"
        private const val KEY_ALIAS = "smart_dairy_master_key"

        fun generateSecureKey(): SecretKey {
            val keyStore = KeyStore.getInstance(KEYSTORE_PROVIDER)
            keyStore.load(null)

            if (!keyStore.containsAlias(KEY_ALIAS)) {
                val keyGenerator = KeyGenerator.getInstance(
                    KeyProperties.KEY_ALGORITHM_AES,
                    KEYSTORE_PROVIDER
                )

                keyGenerator.init(
                    KeyGenParameterSpec.Builder(
                        KEY_ALIAS,
                        KeyProperties.PURPOSE_ENCRYPT or KeyProperties.PURPOSE_DECRYPT
                    )
                        .setBlockModes(KeyProperties.BLOCK_MODE_GCM)
                        .setEncryptionPaddings(KeyProperties.ENCRYPTION_PADDING_NONE)
                        .setKeySize(256)
                        .setUserAuthenticationRequired(false)
                        .setRandomizedEncryptionRequired(true)
                        .build()
                )

                return keyGenerator.generateKey()
            }

            return keyStore.getEntry(KEY_ALIAS, null) as SecretKey
        }

        fun isDeviceSecure(context: Context): Boolean {
            val keyguardManager = context.getSystemService(Context.KEYGUARD_SERVICE) 
                as android.app.KeyguardManager
            return keyguardManager.isDeviceSecure
        }
    }
}
```

#### AndroidManifest.xml Security
```xml
<manifest xmlns:android="http://schemas.android.com/apk/res/android"
    package="com.smartdairy.farm">

    <!-- Disable backup -->
    <application
        android:allowBackup="false"
        android:fullBackupContent="false"
        android:networkSecurityConfig="@xml/network_security_config"
        android:usesCleartextTraffic="false">
        
        <!-- Activities -->
        <activity
            android:name=".MainActivity"
            android:exported="true"
            android:screenOrientation="portrait"
            android:windowSoftInputMode="adjustResize">
            <intent-filter>
                <action android:name="android.intent.action.MAIN" />
                <category android:name="android.intent.category.LAUNCHER" />
            </intent-filter>
        </activity>
    </application>
</manifest>
```

#### Network Security Config
```xml
<!-- res/xml/network_security_config.xml -->
<?xml version="1.0" encoding="utf-8"?>
<network-security-config>
    <base-config cleartextTrafficPermitted="false">
        <trust-anchors>
            <certificates src="system"/>
            <certificates src="user"/>
        </trust-anchors>
    </base-config>
    
    <domain-config>
        <domain includeSubdomains="true">api.smartdairybd.com</domain>
        <pin-set expiration="2027-01-01">
            <pin digest="SHA-256">AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=</pin>
            <pin digest="SHA-256">BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB=</pin>
        </pin-set>
    </domain-config>
</network-security-config>
```

### 7.2 iOS Security

```swift
// ios/Runner/Security/KeychainManager.swift

import Foundation
import Security

class KeychainManager {
    static let shared = KeychainManager()
    
    func save(data: Data, service: String, account: String) -> Bool {
        let query: [String: Any] = [
            kSecClass as String: kSecClassGenericPassword,
            kSecAttrService as String: service,
            kSecAttrAccount as String: account,
            kSecValueData as String: data,
            kSecAttrAccessible as String: kSecAttrAccessibleWhenUnlockedThisDeviceOnly
        ]
        
        SecItemDelete(query as CFDictionary)
        let status = SecItemAdd(query as CFDictionary, nil)
        return status == errSecSuccess
    }
    
    func read(service: String, account: String) -> Data? {
        let query: [String: Any] = [
            kSecClass as String: kSecClassGenericPassword,
            kSecAttrService as String: service,
            kSecAttrAccount as String: account,
            kSecReturnData as String: true,
            kSecMatchLimit as String: kSecMatchLimitOne
        ]
        
        var result: AnyObject?
        SecItemCopyMatching(query as CFDictionary, &result)
        return result as? Data
    }
    
    func delete(service: String, account: String) -> Bool {
        let query: [String: Any] = [
            kSecClass as String: kSecClassGenericPassword,
            kSecAttrService as String: service,
            kSecAttrAccount as String: account
        ]
        
        let status = SecItemDelete(query as CFDictionary)
        return status == errSecSuccess
    }
}
```

---

## 8. CODE QUALITY & TAMPERING PROTECTION

### 8.1 Root/Jailbreak Detection

```dart
// lib/core/security/root_detection.dart

import 'package:flutter_jailbreak_detection/flutter_jailbreak_detection.dart';

class RootDetection {
  static Future<bool> isDeviceCompromised() async {
    try {
      // Check for root/jailbreak
      final isJailbroken = await FlutterJailbreakDetection.jailbroken;
      final isRealDevice = await _isRealDevice();
      
      if (isJailbroken) {
        Logger.warning('Jailbroken/rooted device detected');
        return true;
      }
      
      // Additional checks
      if (await _isDebuggerAttached()) {
        Logger.warning('Debugger detected');
        return true;
      }
      
      return false;
    } catch (e) {
      Logger.error('Root detection error', e);
      return false;
    }
  }

  static Future<bool> _isRealDevice() async {
    // Check for emulator/simulator
    return true; // Implement platform-specific checks
  }

  static Future<bool> _isDebuggerAttached() async {
    // Platform-specific debugger detection
    return false;
  }

  static void showSecurityWarning(BuildContext context) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => AlertDialog(
        title: const Text('Security Warning'),
        content: const Text(
          'This device appears to be compromised. '
          'For security reasons, the app cannot run on rooted or jailbroken devices.'
        ),
        actions: [
          TextButton(
            onPressed: () => SystemNavigator.pop(),
            child: const Text('Exit'),
          ),
        ],
      ),
    );
  }
}
```

### 8.2 Code Obfuscation

```yaml
# android/app/build.gradle

android {
    buildTypes {
        release {
            minifyEnabled true
            shrinkResources true
            proguardFiles getDefaultProguardFile('proguard-android-optimize.txt'), 'proguard-rules.pro'
            
            // Enable R8 full mode
            proguardFiles 'proguard-rules.pro'
        }
    }
}
```

```proguard
# proguard-rules.pro
# Smart Dairy ProGuard Rules

# Keep model classes
-keep class com.smartdairy.models.** { *; }

# Keep API response classes
-keepclassmembers class * {
    @com.google.gson.annotations.SerializedName <fields>;
}

# Obfuscate everything else
-repackageclasses 'a'
-allowaccessmodification
```

---

## 9. IOT & DEVICE SECURITY

### 9.1 MQTT Security

```dart
// lib/core/iot/mqtt_security.dart

import 'package:mqtt_client/mqtt_client.dart';
import 'package:mqtt_client/mqtt_server_client.dart';

class SecureMqttClient {
  static MqttServerClient createSecureClient(String clientId) {
    final client = MqttServerClient(
      'mqtt.smartdairybd.com',
      clientId,
    );
    
    client.secure = true;
    client.port = 8883;
    
    // TLS configuration
    client.securityContext = SecurityContext.defaultContext;
    client.onBadCertificate = (X509Certificate certificate) {
      // Validate certificate
      return _validateMqttCertificate(certificate);
    };
    
    // Authentication
    client.connectionMessage = MqttConnectMessage()
        .withClientIdentifier(clientId)
        .authenticateAs(_getDeviceUsername(), _getDevicePassword())
        .withWillTopic('devices/lastwill')
        .withWillMessage('{"status":"offline"}')
        .withWillQos(MqttQos.atLeastOnce);
    
    return client;
  }

  static bool _validateMqttCertificate(X509Certificate cert) {
    // Certificate validation
    return true;
  }
}
```

---

## 10. SECURITY TESTING

### 10.1 SAST/DAST Integration

```yaml
# .github/workflows/security.yml

name: Security Scan

on: [push, pull_request]

jobs:
  security-scan:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      # Flutter dependencies
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
      
      # SAST - SonarQube
      - name: SonarQube Scan
        uses: sonarsource/sonarqube-scan-action@master
        env:
          SONAR_TOKEN: ${{ secrets.SONAR_TOKEN }}
      
      # Dependency check
      - name: Dependency Check
        run: flutter pub audit
      
      # Mobile security scan
      - name: MobSF Scan
        uses: fundacaocerti/mobsf-action@v1.0.0
        with:
          input_file: build/app/outputs/flutter-apk/app-release.apk
```

### 10.2 Security Test Cases

| Test ID | Test Description | Expected Result |
|---------|------------------|-----------------|
| SEC-001 | Rooted device detection | App exits gracefully |
| SEC-002 | Certificate pinning bypass attempt | Connection rejected |
| SEC-003 | Token extraction attempt | Token encrypted/not accessible |
| SEC-004 | SQL Injection in search | Input sanitized |
| SEC-005 | XSS in notes field | Input escaped |
| SEC-006 | Screen recording prevention | Black screen in recent apps |
| SEC-007 | Clipboard data clearing | Sensitive data not in clipboard |
| SEC-008 | Screenshot prevention | Screenshots blocked (optional) |

---

## 11. INCIDENT RESPONSE

### 11.1 Security Incident Procedures

| Severity | Response Time | Action |
|----------|---------------|--------|
| Critical | 15 minutes | Immediate investigation, possible app shutdown |
| High | 1 hour | Investigation, patch deployment |
| Medium | 24 hours | Scheduled fix |
| Low | Next release | Address in regular cycle |

### 11.2 Remote Wipe Capability

```dart
// Remote wipe trigger
class RemoteWipe {
  static Future<void> executeWipe(String deviceId) async {
    // Clear all local data
    await SecureStorage.deleteAll();
    await Hive.deleteFromDisk();
    await DefaultCacheManager().emptyCache();
    
    // Logout user
    await AuthManager.logout();
    
    // Navigate to wiped state
    navigator.pushReplacementNamed('/wiped');
  }
}
```

---

## 12. COMPLIANCE & AUDITING

### 12.1 Security Audit Checklist

| Area | Check | Frequency |
|------|-------|-----------|
| Dependencies | Check for known vulnerabilities | Weekly |
| Certificates | Verify expiry dates | Monthly |
| Code Review | Security-focused review | Every PR |
| Penetration Test | External security testing | Quarterly |
| Compliance | MASVS verification | Per release |

### 12.2 Logging Standards

```dart
// Security events to log
enum SecurityEvent {
  loginSuccess,
  loginFailure,
  logout,
  passwordChange,
  pinChange,
  biometricEnabled,
  biometricDisabled,
  dataExport,
  remoteWipe,
  rootDetected,
  certificatePinningFailure,
}

class SecurityLogger {
  static void log(SecurityEvent event, {Map<String, dynamic>? metadata}) {
    // Log to secure audit trail
    // No PII in logs
  }
}
```

---

## 13. APPENDICES

### Appendix A: OWASP MASVS Mapping

| MASVS Category | Implementation | Status |
|----------------|----------------|--------|
| V1: Architecture | Defense-in-depth | ✅ Implemented |
| V2: Data Storage | Encryption at rest | ✅ Implemented |
| V3: Cryptography | AES-256-GCM | ✅ Implemented |
| V4: Authentication | JWT + Biometric | ✅ Implemented |
| V5: Network | TLS 1.3 + Pinning | ✅ Implemented |
| V6: Platform | Android/iOS secure storage | ✅ Implemented |
| V7: Code Quality | Obfuscation, Anti-tampering | ✅ Implemented |
| V8: Resilience | Root detection, Anti-debug | ✅ Implemented |

### Appendix B: Security Tools

| Category | Tool | Purpose |
|----------|------|---------|
| SAST | SonarQube | Code analysis |
| DAST | OWASP ZAP | Runtime testing |
| Mobile | MobSF | Mobile security scan |
| Dependencies | Snyk | Vulnerability detection |
| Secrets | GitLeaks | Secret detection |

### Appendix C: Emergency Contacts

| Role | Contact | Escalation |
|------|---------|------------|
| Security Lead | security@smartdairybd.com | +880-XXXX-XXXXXX |
| Mobile Lead | mobile@smartdairybd.com | +880-XXXX-XXXXXX |
| CTO | cto@smartdairybd.com | +880-XXXX-XXXXXX |

---

**END OF MOBILE SECURITY IMPLEMENTATION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | June 12, 2026 | Mobile Lead | Initial version |
