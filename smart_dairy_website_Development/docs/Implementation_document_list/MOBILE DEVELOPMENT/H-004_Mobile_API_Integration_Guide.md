# Mobile API Integration Guide

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | H-004 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Backend Lead |
| **Status** | Approved |
| **Classification** | Internal Use |

### Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Mobile Lead | Initial version |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [HTTP Client Setup](#2-http-client-setup)
3. [Authentication Flow](#3-authentication-flow)
4. [API Service Layer](#4-api-service-layer)
5. [Error Handling](#5-error-handling)
6. [Network Connectivity](#6-network-connectivity)
7. [Request/Response Models](#7-requestresponse-models)
8. [Pagination](#8-pagination)
9. [File Uploads](#9-file-uploads)
10. [Caching Strategy](#10-caching-strategy)
11. [API Endpoints](#11-api-endpoints)
12. [Security](#12-security)
13. [Testing](#13-testing)
14. [Performance](#14-performance)
15. [Logging](#15-logging)
16. [Appendices](#16-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive guidelines for integrating Smart Dairy Ltd's RESTful API services into the Flutter mobile application. It establishes coding standards, architectural patterns, and best practices for robust API communication.

### 1.2 Scope

This guide covers:
- HTTP client configuration and management
- Authentication and authorization flows
- Data modeling and serialization
- Error handling strategies
- Network optimization techniques
- Testing methodologies

### 1.3 API Architecture Overview

Smart Dairy API follows RESTful architecture principles with the following characteristics:

```
┌─────────────────────────────────────────────────────────────────┐
│                    API Architecture                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│   ┌─────────────┐    HTTPS/JSON    ┌─────────────────────────┐  │
│   │   Mobile    │◄────────────────►│   API Gateway           │  │
│   │   App       │                  │   (Nginx/Load Balancer) │  │
│   └─────────────┘                  └─────────────────────────┘  │
│                                              │                   │
│                                              ▼                   │
│                              ┌─────────────────────────┐        │
│                              │   Backend Services      │        │
│                              │   - Authentication      │        │
│                              │   - Farm Management     │        │
│                              │   - Milk Production     │        │
│                              │   - Animal Tracking     │        │
│                              │   - Reports & Analytics │        │
│                              └─────────────────────────┘        │
│                                              │                   │
│                                              ▼                   │
│                              ┌─────────────────────────┐        │
│                              │   Database Layer        │        │
│                              │   (PostgreSQL/Redis)    │        │
│                              └─────────────────────────┘        │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.4 Tech Stack

| Component | Technology | Version |
|-----------|-----------|---------|
| HTTP Client | Dio | ^5.4.0 |
| State Management | Riverpod/Bloc | Latest |
| JSON Serialization | Freezed + json_serializable | Latest |
| Secure Storage | flutter_secure_storage | ^9.0.0 |
| Connectivity | connectivity_plus | ^5.0.0 |
| Testing | Mockito + Mocktail | Latest |

---

## 2. HTTP Client Setup

### 2.1 Dio Configuration

Create a centralized Dio client with proper configuration:

```dart
// lib/core/network/dio_client.dart

import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';

class DioClient {
  static Dio? _dio;
  static const int _defaultConnectTimeout = 30000; // 30 seconds
  static const int _defaultReceiveTimeout = 30000; // 30 seconds
  static const int _defaultSendTimeout = 30000; // 30 seconds

  static Dio get dio {
    _dio ??= _createDio();
    return _dio!;
  }

  static Dio _createDio() {
    final dio = Dio(
      BaseOptions(
        baseUrl: ApiConfig.baseUrl,
        connectTimeout: const Duration(milliseconds: _defaultConnectTimeout),
        receiveTimeout: const Duration(milliseconds: _defaultReceiveTimeout),
        sendTimeout: const Duration(milliseconds: _defaultSendTimeout),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-App-Version': ApiConfig.appVersion,
          'X-Platform': defaultTargetPlatform.name,
        },
        validateStatus: (status) {
          return status != null && status >= 200 && status < 300;
        },
      ),
    );

    // Add interceptors in order
    dio.interceptors.addAll([
      LoggingInterceptor(),
      AuthInterceptor(),
      RetryInterceptor(dio: dio),
      CacheInterceptor(),
    ]);

    return dio;
  }

  static void updateBaseUrl(String baseUrl) {
    dio.options.baseUrl = baseUrl;
  }

  static void reset() {
    _dio = null;
  }
}

// API Configuration
class ApiConfig {
  ApiConfig._();

  static String get baseUrl {
    const env = String.fromEnvironment('ENV', defaultValue: 'production');
    switch (env) {
      case 'staging':
        return stagingUrl;
      case 'development':
        return devUrl;
      case 'production':
      default:
        return productionUrl;
    }
  }

  static const String productionUrl = 'https://api.smartdairybd.com/v1';
  static const String stagingUrl = 'https://api-staging.smartdairybd.com/v1';
  static const String devUrl = 'http://localhost:8000/v1';

  static const String appVersion = '1.0.0';
  static const String apiVersion = 'v1';
}
```

### 2.2 Logging Interceptor

```dart
// lib/core/network/interceptors/logging_interceptor.dart

import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:logger/logger.dart';

class LoggingInterceptor extends Interceptor {
  final Logger _logger = Logger(
    printer: PrettyPrinter(
      methodCount: 0,
      errorMethodCount: 5,
      lineLength: 120,
      colors: true,
      printEmojis: true,
    ),
  );

  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    if (kDebugMode) {
      _logger.i('╔═══════════════════════════════════════════════════════════╗');
      _logger.i('║ REQUEST ║ ${options.method.toUpperCase()} ${options.uri}');
      _logger.i('╠═══════════════════════════════════════════════════════════╣');
      _logger.i('║ Headers:');
      options.headers.forEach((key, value) {
        if (key.toLowerCase() != 'authorization') {
          _logger.i('║   $key: $value');
        } else {
          _logger.i('║   $key: ***REDACTED***');
        }
      });
      if (options.queryParameters.isNotEmpty) {
        _logger.i('║ Query Parameters:');
        options.queryParameters.forEach((key, value) {
          _logger.i('║   $key: $value');
        });
      }
      if (options.data != null) {
        _logger.i('║ Body: ${options.data}');
      }
      _logger.i('╚═══════════════════════════════════════════════════════════╝');
    }
    super.onRequest(options, handler);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    if (kDebugMode) {
      _logger.d('╔═══════════════════════════════════════════════════════════╗');
      _logger.d('║ RESPONSE ║ ${response.statusCode} ${response.requestOptions.uri}');
      _logger.d('╠═══════════════════════════════════════════════════════════╣');
      _logger.d('║ Headers:');
      response.headers.forEach((key, values) {
        _logger.d('║   $key: ${values.join(', ')}');
      });
      _logger.d('║ Body: ${response.data}');
      _logger.d('╚═══════════════════════════════════════════════════════════╝');
    }
    super.onResponse(response, handler);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    if (kDebugMode) {
      _logger.e('╔═══════════════════════════════════════════════════════════╗');
      _logger.e('║ ERROR ║ ${err.type} ${err.requestOptions.uri}');
      _logger.e('╠═══════════════════════════════════════════════════════════╣');
      _logger.e('║ Message: ${err.message}');
      _logger.e('║ Status Code: ${err.response?.statusCode}');
      if (err.response?.data != null) {
        _logger.e('║ Response Data: ${err.response?.data}');
      }
      if (err.stackTrace != null) {
        _logger.e('║ StackTrace: ${err.stackTrace}');
      }
      _logger.e('╚═══════════════════════════════════════════════════════════╝');
    }
    super.onError(err, handler);
  }
}
```

### 2.3 Retry Interceptor

```dart
// lib/core/network/interceptors/retry_interceptor.dart

import 'dart:async';
import 'dart:io';
import 'package:dio/dio.dart';

class RetryInterceptor extends Interceptor {
  final Dio dio;
  final int retries;
  final Duration retryDelay;

  RetryInterceptor({
    required this.dio,
    this.retries = 3,
    this.retryDelay = const Duration(seconds: 1),
  });

  @override
  Future<void> onError(
    DioException err,
    ErrorInterceptorHandler handler,
  ) async {
    final extra = err.requestOptions.extra;
    final retryCount = extra['retry_count'] as int? ?? 0;

    if (_shouldRetry(err) && retryCount < retries) {
      extra['retry_count'] = retryCount + 1;
      
      // Exponential backoff
      final delay = retryDelay * (retryCount + 1);
      await Future.delayed(delay);

      try {
        final response = await dio.fetch(err.requestOptions);
        return handler.resolve(response);
      } catch (e) {
        return handler.next(err);
      }
    }
    return handler.next(err);
  }

  bool _shouldRetry(DioException error) {
    return error.type == DioExceptionType.connectionTimeout ||
        error.type == DioExceptionType.sendTimeout ||
        error.type == DioExceptionType.receiveTimeout ||
        error.error is SocketException ||
        (error.response?.statusCode ?? 0) >= 500;
  }
}
```

---

## 3. Authentication Flow

### 3.1 JWT Handling

```dart
// lib/core/auth/token_manager.dart

import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:jwt_decoder/jwt_decoder.dart';

class TokenManager {
  static const _storage = FlutterSecureStorage(
    aOptions: AndroidOptions(
      encryptedSharedPreferences: true,
    ),
    iOptions: IOSOptions(
      accessibility: KeychainAccessibility.first_unlock_this_device,
    ),
  );

  static const _accessTokenKey = 'access_token';
  static const _refreshTokenKey = 'refresh_token';
  static const _tokenExpiryKey = 'token_expiry';

  static Future<void> saveTokens({
    required String accessToken,
    required String refreshToken,
  }) async {
    await Future.wait([
      _storage.write(key: _accessTokenKey, value: accessToken),
      _storage.write(key: _refreshTokenKey, value: refreshToken),
      _storage.write(
        key: _tokenExpiryKey,
        value: DateTime.now()
            .add(const Duration(minutes: 15))
            .millisecondsSinceEpoch
            .toString(),
      ),
    ]);
  }

  static Future<String?> getAccessToken() async {
    return await _storage.read(key: _accessTokenKey);
  }

  static Future<String?> getRefreshToken() async {
    return await _storage.read(key: _refreshTokenKey);
  }

  static Future<bool> hasValidToken() async {
    final token = await getAccessToken();
    if (token == null) return false;
    return !JwtDecoder.isExpired(token);
  }

  static Future<bool> isTokenExpiringSoon({Duration threshold = const Duration(minutes: 5)}) async {
    final token = await getAccessToken();
    if (token == null) return true;

    final expiry = JwtDecoder.getExpirationDate(token);
    return DateTime.now().add(threshold).isAfter(expiry);
  }

  static Future<void> clearTokens() async {
    await _storage.deleteAll();
  }
}
```

### 3.2 Auth Interceptor

```dart
// lib/core/network/interceptors/auth_interceptor.dart

import 'package:dio/dio.dart';
import '../../auth/token_manager.dart';

class AuthInterceptor extends Interceptor {
  static bool _isRefreshing = false;
  static final List<Function()> _pendingRequests = [];

  @override
  Future<void> onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    // Skip if no-auth header is present
    if (options.headers['X-No-Auth'] == 'true') {
      options.headers.remove('X-No-Auth');
      return handler.next(options);
    }

    final token = await TokenManager.getAccessToken();
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }

    return handler.next(options);
  }

  @override
  Future<void> onError(
    DioException err,
    ErrorInterceptorHandler handler,
  ) async {
    if (err.response?.statusCode == 401) {
      final refreshToken = await TokenManager.getRefreshToken();
      
      if (refreshToken == null) {
        // No refresh token, clear auth and notify
        await TokenManager.clearTokens();
        _onUnauthorized();
        return handler.next(err);
      }

      if (_isRefreshing) {
        // Queue request
        _pendingRequests.add(() async {
          final token = await TokenManager.getAccessToken();
          if (token != null) {
            err.requestOptions.headers['Authorization'] = 'Bearer $token';
            try {
              final response = await DioClient.dio.fetch(err.requestOptions);
              handler.resolve(response);
            } catch (e) {
              handler.next(err);
            }
          }
        });
        return;
      }

      _isRefreshing = true;

      try {
        final newToken = await _refreshToken(refreshToken);
        
        // Update request with new token
        err.requestOptions.headers['Authorization'] = 'Bearer $newToken';
        
        // Retry request
        final response = await DioClient.dio.fetch(err.requestOptions);
        handler.resolve(response);

        // Process pending requests
        for (var request in _pendingRequests) {
          request();
        }
        _pendingRequests.clear();
      } catch (e) {
        // Refresh failed, clear tokens
        await TokenManager.clearTokens();
        _onUnauthorized();
        handler.next(err);
      } finally {
        _isRefreshing = false;
      }
    } else {
      handler.next(err);
    }
  }

  Future<String> _refreshToken(String refreshToken) async {
    final response = await DioClient.dio.post(
      '/auth/refresh',
      data: {'refresh_token': refreshToken},
      options: Options(headers: {'X-No-Auth': 'true'}),
    );

    final accessToken = response.data['data']['access_token'] as String;
    final newRefreshToken = response.data['data']['refresh_token'] as String;

    await TokenManager.saveTokens(
      accessToken: accessToken,
      refreshToken: newRefreshToken,
    );

    return accessToken;
  }

  void _onUnauthorized() {
    // Emit event to auth bloc/state management
    // Navigate to login screen
  }
}
```

### 3.3 Authentication Repository

```dart
// lib/features/auth/data/repositories/auth_repository.dart

import 'package:dartz/dartz.dart';
import '../../../../core/errors/failures.dart';
import '../../../../core/network/dio_client.dart';
import '../../../../core/auth/token_manager.dart';
import '../models/login_request.dart';
import '../models/login_response.dart';
import '../models/user_model.dart';

abstract class AuthRepository {
  Future<Either<Failure, UserModel>> login(LoginRequest request);
  Future<Either<Failure, void>> logout();
  Future<Either<Failure, void>> register(RegisterRequest request);
  Future<Either<Failure, void>> forgotPassword(String email);
  Future<Either<Failure, void>> resetPassword(String token, String newPassword);
  Future<Either<Failure, UserModel?>> checkAuthStatus();
}

class AuthRepositoryImpl implements AuthRepository {
  @override
  Future<Either<Failure, UserModel>> login(LoginRequest request) async {
    try {
      final response = await DioClient.dio.post(
        '/auth/login',
        data: request.toJson(),
        options: Options(headers: {'X-No-Auth': 'true'}),
      );

      final loginResponse = LoginResponse.fromJson(response.data['data']);

      await TokenManager.saveTokens(
        accessToken: loginResponse.accessToken,
        refreshToken: loginResponse.refreshToken,
      );

      return Right(loginResponse.user);
    } on DioException catch (e) {
      return Left(ServerFailure.fromDioException(e));
    } catch (e) {
      return Left(UnknownFailure(message: e.toString()));
    }
  }

  @override
  Future<Either<Failure, void>> logout() async {
    try {
      await DioClient.dio.post('/auth/logout');
      await TokenManager.clearTokens();
      return const Right(null);
    } on DioException catch (e) {
      // Still clear local tokens even if server logout fails
      await TokenManager.clearTokens();
      return Left(ServerFailure.fromDioException(e));
    }
  }

  @override
  Future<Either<Failure, void>> register(RegisterRequest request) async {
    try {
      await DioClient.dio.post(
        '/auth/register',
        data: request.toJson(),
        options: Options(headers: {'X-No-Auth': 'true'}),
      );
      return const Right(null);
    } on DioException catch (e) {
      return Left(ServerFailure.fromDioException(e));
    }
  }

  @override
  Future<Either<Failure, void>> forgotPassword(String email) async {
    try {
      await DioClient.dio.post(
        '/auth/forgot-password',
        data: {'email': email},
        options: Options(headers: {'X-No-Auth': 'true'}),
      );
      return const Right(null);
    } on DioException catch (e) {
      return Left(ServerFailure.fromDioException(e));
    }
  }

  @override
  Future<Either<Failure, void>> resetPassword(
    String token,
    String newPassword,
  ) async {
    try {
      await DioClient.dio.post(
        '/auth/reset-password',
        data: {
          'token': token,
          'password': newPassword,
        },
        options: Options(headers: {'X-No-Auth': 'true'}),
      );
      return const Right(null);
    } on DioException catch (e) {
      return Left(ServerFailure.fromDioException(e));
    }
  }

  @override
  Future<Either<Failure, UserModel?>> checkAuthStatus() async {
    try {
      final isValid = await TokenManager.hasValidToken();
      if (!isValid) return const Right(null);

      final response = await DioClient.dio.get('/auth/me');
      return Right(UserModel.fromJson(response.data['data']));
    } on DioException catch (e) {
      if (e.response?.statusCode == 401) {
        return const Right(null);
      }
      return Left(ServerFailure.fromDioException(e));
    }
  }
}
```

---

## 4. API Service Layer

### 4.1 Repository Pattern

```dart
// lib/core/network/repository/base_repository.dart

import 'package:dartz/dartz.dart';
import 'package:dio/dio.dart';
import '../../errors/failures.dart';

abstract class BaseRepository {
  Future<Either<Failure, T>> handleRequest<T>(
    Future<T> Function() request,
  ) async {
    try {
      final result = await request();
      return Right(result);
    } on DioException catch (e) {
      return Left(ServerFailure.fromDioException(e));
    } catch (e) {
      return Left(UnknownFailure(message: e.toString()));
    }
  }
}
```

### 4.2 Data Sources

```dart
// lib/features/farm/data/datasources/farm_remote_data_source.dart

import 'package:dio/dio.dart';
import '../../../../core/network/dio_client.dart';
import '../models/farm_model.dart';
import '../models/create_farm_request.dart';
import '../models/update_farm_request.dart';

abstract class FarmRemoteDataSource {
  Future<List<FarmModel>> getFarms({int? page, int? limit});
  Future<FarmModel> getFarmById(String id);
  Future<FarmModel> createFarm(CreateFarmRequest request);
  Future<FarmModel> updateFarm(String id, UpdateFarmRequest request);
  Future<void> deleteFarm(String id);
  Future<List<FarmModel>> searchFarms(String query);
}

class FarmRemoteDataSourceImpl implements FarmRemoteDataSource {
  @override
  Future<List<FarmModel>> getFarms({int? page, int? limit}) async {
    final response = await DioClient.dio.get(
      '/farms',
      queryParameters: {
        if (page != null) 'page': page,
        if (limit != null) 'limit': limit,
      },
    );

    return (response.data['data'] as List)
        .map((json) => FarmModel.fromJson(json))
        .toList();
  }

  @override
  Future<FarmModel> getFarmById(String id) async {
    final response = await DioClient.dio.get('/farms/$id');
    return FarmModel.fromJson(response.data['data']);
  }

  @override
  Future<FarmModel> createFarm(CreateFarmRequest request) async {
    final response = await DioClient.dio.post(
      '/farms',
      data: request.toJson(),
    );
    return FarmModel.fromJson(response.data['data']);
  }

  @override
  Future<FarmModel> updateFarm(String id, UpdateFarmRequest request) async {
    final response = await DioClient.dio.put(
      '/farms/$id',
      data: request.toJson(),
    );
    return FarmModel.fromJson(response.data['data']);
  }

  @override
  Future<void> deleteFarm(String id) async {
    await DioClient.dio.delete('/farms/$id');
  }

  @override
  Future<List<FarmModel>> searchFarms(String query) async {
    final response = await DioClient.dio.get(
      '/farms/search',
      queryParameters: {'q': query},
    );

    return (response.data['data'] as List)
        .map((json) => FarmModel.fromJson(json))
        .toList();
  }
}
```

### 4.3 Farm Repository Implementation

```dart
// lib/features/farm/data/repositories/farm_repository_impl.dart

import 'package:dartz/dartz.dart';
import '../../../../core/errors/failures.dart';
import '../../../../core/network/repository/base_repository.dart';
import '../../domain/entities/farm.dart';
import '../../domain/repositories/farm_repository.dart';
import '../datasources/farm_remote_data_source.dart';
import '../models/create_farm_request.dart';
import '../models/update_farm_request.dart';

class FarmRepositoryImpl extends BaseRepository implements FarmRepository {
  final FarmRemoteDataSource remoteDataSource;

  FarmRepositoryImpl({required this.remoteDataSource});

  @override
  Future<Either<Failure, List<Farm>>> getFarms({int? page, int? limit}) {
    return handleRequest(() => remoteDataSource.getFarms(
          page: page,
          limit: limit,
        ));
  }

  @override
  Future<Either<Failure, Farm>> getFarmById(String id) {
    return handleRequest(() => remoteDataSource.getFarmById(id));
  }

  @override
  Future<Either<Failure, Farm>> createFarm(CreateFarmRequest request) {
    return handleRequest(() => remoteDataSource.createFarm(request));
  }

  @override
  Future<Either<Failure, Farm>> updateFarm(String id, UpdateFarmRequest request) {
    return handleRequest(() => remoteDataSource.updateFarm(id, request));
  }

  @override
  Future<Either<Failure, void>> deleteFarm(String id) {
    return handleRequest(() => remoteDataSource.deleteFarm(id));
  }

  @override
  Future<Either<Failure, List<Farm>>> searchFarms(String query) {
    return handleRequest(() => remoteDataSource.searchFarms(query));
  }
}
```

---

## 5. Error Handling

### 5.1 Failure Classes

```dart
// lib/core/errors/failures.dart

import 'package:dio/dio.dart';
import 'package:equatable/equatable.dart';

abstract class Failure extends Equatable {
  final String message;
  final String? code;

  const Failure({
    required this.message,
    this.code,
  });

  @override
  List<Object?> get props => [message, code];
}

class ServerFailure extends Failure {
  const ServerFailure({
    required super.message,
    super.code,
  });

  factory ServerFailure.fromDioException(DioException error) {
    switch (error.type) {
      case DioExceptionType.connectionTimeout:
      case DioExceptionType.sendTimeout:
      case DioExceptionType.receiveTimeout:
        return const ServerFailure(
          message: 'Connection timeout. Please check your internet connection.',
          code: 'TIMEOUT',
        );

      case DioExceptionType.badCertificate:
        return const ServerFailure(
          message: 'Security certificate error. Please update the app.',
          code: 'CERTIFICATE_ERROR',
        );

      case DioExceptionType.badResponse:
        return _handleBadResponse(error.response);

      case DioExceptionType.cancel:
        return const ServerFailure(
          message: 'Request was cancelled.',
          code: 'CANCELLED',
        );

      case DioExceptionType.connectionError:
        return const ServerFailure(
          message: 'No internet connection. Please check your network.',
          code: 'NO_CONNECTION',
        );

      case DioExceptionType.unknown:
      default:
        return ServerFailure(
          message: error.message ?? 'An unexpected error occurred.',
          code: 'UNKNOWN',
        );
    }
  }

  static ServerFailure _handleBadResponse(Response? response) {
    if (response == null) {
      return const ServerFailure(
        message: 'No response from server.',
        code: 'NO_RESPONSE',
      );
    }

    final statusCode = response.statusCode;
    final data = response.data;

    final message = data?['message'] ?? data?['error'] ?? 'An error occurred';
    final code = data?['code']?.toString() ?? 'HTTP_$statusCode';

    switch (statusCode) {
      case 400:
        return ServerFailure(
          message: message,
          code: 'BAD_REQUEST',
        );
      case 401:
        return ServerFailure(
          message: message,
          code: 'UNAUTHORIZED',
        );
      case 403:
        return ServerFailure(
          message: message,
          code: 'FORBIDDEN',
        );
      case 404:
        return ServerFailure(
          message: message,
          code: 'NOT_FOUND',
        );
      case 422:
        return ServerFailure(
          message: message,
          code: 'VALIDATION_ERROR',
        );
      case 429:
        return ServerFailure(
          message: 'Too many requests. Please try again later.',
          code: 'RATE_LIMIT',
        );
      case 500:
      case 502:
      case 503:
      case 504:
        return ServerFailure(
          message: 'Server error. Please try again later.',
          code: 'SERVER_ERROR',
        );
      default:
        return ServerFailure(
          message: message,
          code: code,
        );
    }
  }
}

class CacheFailure extends Failure {
  const CacheFailure({
    required super.message,
    super.code,
  });
}

class NetworkFailure extends Failure {
  const NetworkFailure({
    required super.message,
    super.code,
  });
}

class UnknownFailure extends Failure {
  const UnknownFailure({
    required super.message,
    super.code,
  });
}
```

### 5.2 Error Handler Widget

```dart
// lib/core/errors/error_handler.dart

import 'package:flutter/material.dart';
import 'failures.dart';

class ErrorHandler {
  static String getUserFriendlyMessage(Failure failure) {
    return failure.message;
  }

  static bool shouldRetry(Failure failure) {
    return failure is ServerFailure &&
        (failure.code == 'TIMEOUT' ||
            failure.code == 'NO_CONNECTION' ||
            failure.code == 'SERVER_ERROR');
  }

  static void showErrorSnackBar(BuildContext context, Failure failure) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(getUserFriendlyMessage(failure)),
        backgroundColor: _getErrorColor(failure),
        action: shouldRetry(failure)
            ? SnackBarAction(
                label: 'Retry',
                textColor: Colors.white,
                onPressed: () {
                  // Trigger retry callback
                },
              )
            : null,
      ),
    );
  }

  static Color _getErrorColor(Failure failure) {
    if (failure.code == 'UNAUTHORIZED') {
      return Colors.orange;
    } else if (failure.code == 'VALIDATION_ERROR') {
      return Colors.blue;
    } else if (failure.code == 'NO_CONNECTION') {
      return Colors.grey.shade700;
    }
    return Colors.red;
  }
}
```

### 5.3 Result Wrapper

```dart
// lib/core/network/result.dart

import 'package:dartz/dartz.dart';
import '../errors/failures.dart';

type Result<T> = Either<Failure, T>;

extension ResultExtension<T> on Result<T> {
  void foldResult({
    required void Function(Failure failure) onFailure,
    required void Function(T data) onSuccess,
  }) {
    fold(onFailure, onSuccess);
  }

  T? getOrNull() {
    return fold((_) => null, (data) => data);
  }

  Failure? failureOrNull() {
    return fold((failure) => failure, (_) => null);
  }
}
```

---

## 6. Network Connectivity

### 6.1 Connectivity Service

```dart
// lib/core/network/connectivity_service.dart

import 'dart:async';
import 'package:connectivity_plus/connectivity_plus.dart';

enum ConnectionStatus {
  online,
  offline,
}

class ConnectivityService {
  final Connectivity _connectivity = Connectivity();
  StreamSubscription<List<ConnectivityResult>>? _subscription;
  final _controller = StreamController<ConnectionStatus>.broadcast();

  Stream<ConnectionStatus> get statusStream => _controller.stream;

  void initialize() {
    _subscription = _connectivity.onConnectivityChanged.listen(_updateStatus);
    _checkInitialStatus();
  }

  Future<void> _checkInitialStatus() async {
    final result = await _connectivity.checkConnectivity();
    _updateStatus(result);
  }

  void _updateStatus(List<ConnectivityResult> results) {
    final hasConnection = results.any((result) =>
        result == ConnectivityResult.wifi ||
        result == ConnectivityResult.mobile ||
        result == ConnectivityResult.ethernet);

    _controller.add(hasConnection ? ConnectionStatus.online : ConnectionStatus.offline);
  }

  Future<bool> isConnected() async {
    final result = await _connectivity.checkConnectivity();
    return result.any((r) =>
        r == ConnectivityResult.wifi ||
        r == ConnectivityResult.mobile ||
        r == ConnectivityResult.ethernet);
  }

  void dispose() {
    _subscription?.cancel();
    _controller.close();
  }
}
```

### 6.2 Network-Aware Widget

```dart
// lib/core/widgets/network_aware_widget.dart

import 'package:flutter/material.dart';
import '../network/connectivity_service.dart';

class NetworkAwareWidget extends StatefulWidget {
  final Widget child;
  final Widget? offlineWidget;
  final VoidCallback? onOnline;
  final VoidCallback? onOffline;

  const NetworkAwareWidget({
    super.key,
    required this.child,
    this.offlineWidget,
    this.onOnline,
    this.onOffline,
  });

  @override
  State<NetworkAwareWidget> createState() => _NetworkAwareWidgetState();
}

class _NetworkAwareWidgetState extends State<NetworkAwareWidget> {
  final _connectivityService = ConnectivityService();
  ConnectionStatus _status = ConnectionStatus.online;

  @override
  void initState() {
    super.initState();
    _connectivityService.initialize();
    _connectivityService.statusStream.listen((status) {
      setState(() => _status = status);
      if (status == ConnectionStatus.online) {
        widget.onOnline?.call();
      } else {
        widget.onOffline?.call();
      }
    });
  }

  @override
  void dispose() {
    _connectivityService.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (_status == ConnectionStatus.offline && widget.offlineWidget != null) {
      return Column(
        children: [
          _buildOfflineBanner(),
          Expanded(child: widget.offlineWidget!),
        ],
      );
    }

    return Column(
      children: [
        if (_status == ConnectionStatus.offline) _buildOfflineBanner(),
        Expanded(child: widget.child),
      ],
    );
  }

  Widget _buildOfflineBanner() {
    return Container(
      width: double.infinity,
      color: Colors.grey.shade800,
      padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 16),
      child: const Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.wifi_off, color: Colors.white, size: 16),
          SizedBox(width: 8),
          Text(
            'You are offline',
            style: TextStyle(color: Colors.white, fontSize: 12),
          ),
        ],
      ),
    );
  }
}
```

---

## 7. Request/Response Models

### 7.1 Freezed Models

```dart
// lib/features/farm/data/models/farm_model.dart

import 'package:freezed_annotation/freezed_annotation.dart';
import '../../domain/entities/farm.dart';

part 'farm_model.freezed.dart';
part 'farm_model.g.dart';

@freezed
class FarmModel with _$FarmModel {
  const factory FarmModel({
    required String id,
    required String name,
    required String ownerId,
    String? description,
    @JsonKey(name: 'total_animals') required int totalAnimals,
    @JsonKey(name: 'daily_production') required double dailyProduction,
    required FarmAddressModel address,
    @JsonKey(name: 'created_at') required DateTime createdAt,
    @JsonKey(name: 'updated_at') required DateTime updatedAt,
    @Default(false) bool isActive,
  }) = _FarmModel;

  factory FarmModel.fromJson(Map<String, dynamic> json) =>
      _$FarmModelFromJson(json);
}

@freezed
class FarmAddressModel with _$FarmAddressModel {
  const factory FarmAddressModel({
    required String street,
    required String city,
    required String district,
    @JsonKey(name: 'postal_code') String? postalCode,
    required String country,
    double? latitude,
    double? longitude,
  }) = _FarmAddressModel;

  factory FarmAddressModel.fromJson(Map<String, dynamic> json) =>
      _$FarmAddressModelFromJson(json);
}

// Extension for domain mapping
extension FarmModelExtension on FarmModel {
  Farm toDomain() => Farm(
        id: id,
        name: name,
        ownerId: ownerId,
        description: description,
        totalAnimals: totalAnimals,
        dailyProduction: dailyProduction,
        address: address.toDomain(),
        createdAt: createdAt,
        updatedAt: updatedAt,
        isActive: isActive,
      );
}
```

### 7.2 Request Models

```dart
// lib/features/farm/data/models/create_farm_request.dart

import 'package:freezed_annotation/freezed_annotation.dart';

part 'create_farm_request.freezed.dart';
part 'create_farm_request.g.dart';

@freezed
class CreateFarmRequest with _$CreateFarmRequest {
  const factory CreateFarmRequest({
    required String name,
    String? description,
    required FarmAddressRequest address,
  }) = _CreateFarmRequest;

  factory CreateFarmRequest.fromJson(Map<String, dynamic> json) =>
      _$CreateFarmRequestFromJson(json);
}

@freezed
class FarmAddressRequest with _$FarmAddressRequest {
  const factory FarmAddressRequest({
    required String street,
    required String city,
    required String district,
    @JsonKey(name: 'postal_code') String? postalCode,
    @Default('Bangladesh') String country,
    double? latitude,
    double? longitude,
  }) = _FarmAddressRequest;

  factory FarmAddressRequest.fromJson(Map<String, dynamic> json) =>
      _$FarmAddressRequestFromJson(json);
}
```

### 7.3 API Response Wrapper

```dart
// lib/core/network/api_response.dart

import 'package:freezed_annotation/freezed_annotation.dart';

part 'api_response.freezed.dart';
part 'api_response.g.dart';

@freezed
class ApiResponse<T> with _$ApiResponse<T> {
  const factory ApiResponse({
    required bool success,
    String? message,
    T? data,
    ApiError? error,
    ApiMeta? meta,
  }) = _ApiResponse;

  factory ApiResponse.fromJson(
    Map<String, dynamic> json,
    T Function(Object?) fromJsonT,
  ) =>
      _$ApiResponseFromJson(json, fromJsonT);
}

@freezed
class ApiError with _$ApiError {
  const factory ApiError({
    required String code,
    required String message,
    Map<String, List<String>>? errors,
  }) = _ApiError;

  factory ApiError.fromJson(Map<String, dynamic> json) =>
      _$ApiErrorFromJson(json);
}

@freezed
class ApiMeta with _$ApiMeta {
  const factory ApiMeta({
    required int currentPage,
    required int lastPage,
    required int perPage,
    required int total,
    @JsonKey(name: 'has_more') required bool hasMore,
  }) = _ApiMeta;

  factory ApiMeta.fromJson(Map<String, dynamic> json) =>
      _$ApiMetaFromJson(json);
}
```

---

## 8. Pagination

### 8.1 Pagination Controller

```dart
// lib/core/pagination/pagination_controller.dart

import 'dart:async';
import 'package:flutter/foundation.dart';

class PaginationController<T> extends ChangeNotifier {
  final Future<PaginationResult<T>> Function(int page, int limit) fetchPage;
  final int itemsPerPage;

  PaginationController({
    required this.fetchPage,
    this.itemsPerPage = 20,
  });

  final List<T> _items = [];
  int _currentPage = 1;
  bool _isLoading = false;
  bool _hasMore = true;
  String? _error;

  List<T> get items => List.unmodifiable(_items);
  bool get isLoading => _isLoading;
  bool get hasMore => _hasMore;
  String? get error => _error;
  bool get isEmpty => _items.isEmpty && !_isLoading;

  Future<void> loadFirstPage() async {
    _currentPage = 1;
    _items.clear();
    _hasMore = true;
    _error = null;
    notifyListeners();
    await _loadPage();
  }

  Future<void> loadNextPage() async {
    if (_isLoading || !_hasMore) return;
    await _loadPage();
  }

  Future<void> _loadPage() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final result = await fetchPage(_currentPage, itemsPerPage);
      
      _items.addAll(result.items);
      _hasMore = result.hasMore;
      _currentPage++;
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> refresh() async {
    await loadFirstPage();
  }

  void addItem(T item) {
    _items.insert(0, item);
    notifyListeners();
  }

  void updateItem(String id, T Function(T) update, String Function(T) getId) {
    final index = _items.indexWhere((item) => getId(item) == id);
    if (index != -1) {
      _items[index] = update(_items[index]);
      notifyListeners();
    }
  }

  void removeItem(String id, String Function(T) getId) {
    _items.removeWhere((item) => getId(item) == id);
    notifyListeners();
  }

  @override
  void dispose() {
    _items.clear();
    super.dispose();
  }
}

class PaginationResult<T> {
  final List<T> items;
  final bool hasMore;

  PaginationResult({
    required this.items,
    required this.hasMore,
  });
}
```

### 8.2 Paginated List Widget

```dart
// lib/core/widgets/paginated_list_view.dart

import 'package:flutter/material.dart';
import '../pagination/pagination_controller.dart';

class PaginatedListView<T> extends StatefulWidget {
  final PaginationController<T> controller;
  final Widget Function(BuildContext context, T item, int index) itemBuilder;
  final Widget? separatorBuilder;
  final Widget Function(BuildContext context)? loadingBuilder;
  final Widget Function(BuildContext context, String error)? errorBuilder;
  final Widget Function(BuildContext context)? emptyBuilder;
  final Future<void> Function()? onRefresh;

  const PaginatedListView({
    super.key,
    required this.controller,
    required this.itemBuilder,
    this.separatorBuilder,
    this.loadingBuilder,
    this.errorBuilder,
    this.emptyBuilder,
    this.onRefresh,
  });

  @override
  State<PaginatedListView<T>> createState() => _PaginatedListViewState<T>();
}

class _PaginatedListViewState<T> extends State<PaginatedListView<T>> {
  final ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    _scrollController.addListener(_onScroll);
    widget.controller.addListener(_onControllerUpdate);
  }

  void _onScroll() {
    if (_scrollController.position.pixels >=
        _scrollController.position.maxScrollExtent - 200) {
      widget.controller.loadNextPage();
    }
  }

  void _onControllerUpdate() {
    setState(() {});
  }

  @override
  void dispose() {
    _scrollController.dispose();
    widget.controller.removeListener(_onControllerUpdate);
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final items = widget.controller.items;
    final isLoading = widget.controller.isLoading;
    final hasMore = widget.controller.hasMore;
    final error = widget.controller.error;

    if (items.isEmpty && isLoading) {
      return widget.loadingBuilder?.call(context) ??
          const Center(child: CircularProgressIndicator());
    }

    if (items.isEmpty && error != null) {
      return widget.errorBuilder?.call(context, error) ??
          _buildDefaultError(context, error);
    }

    if (items.isEmpty) {
      return widget.emptyBuilder?.call(context) ??
          const Center(child: Text('No items found'));
    }

    return RefreshIndicator(
      onRefresh: widget.onRefresh ?? widget.controller.refresh,
      child: ListView.separated(
        controller: _scrollController,
        physics: const AlwaysScrollableScrollPhysics(),
        itemCount: items.length + (isLoading || !hasMore ? 1 : 0),
        separatorBuilder: (context, index) {
          if (widget.separatorBuilder != null && index < items.length - 1) {
            return widget.separatorBuilder!;
          }
          return const SizedBox.shrink();
        },
        itemBuilder: (context, index) {
          if (index >= items.length) {
            if (isLoading) {
              return const Padding(
                padding: EdgeInsets.all(16.0),
                child: Center(child: CircularProgressIndicator()),
              );
            }
            return const SizedBox.shrink();
          }
          return widget.itemBuilder(context, items[index], index);
        },
      ),
    );
  }

  Widget _buildDefaultError(BuildContext context, String error) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Icon(Icons.error_outline, size: 48, color: Colors.red),
          const SizedBox(height: 16),
          Text(error, textAlign: TextAlign.center),
          const SizedBox(height: 16),
          ElevatedButton(
            onPressed: widget.controller.refresh,
            child: const Text('Retry'),
          ),
        ],
      ),
    );
  }
}
```

---

## 9. File Uploads

### 9.1 File Upload Service

```dart
// lib/core/network/file_upload_service.dart

import 'dart:io';
import 'package:dio/dio.dart';
import 'package:flutter_image_compress/flutter_image_compress.dart';
import 'package:path_provider/path_provider.dart';
import 'package:mime/mime.dart';
import 'dio_client.dart';

class FileUploadService {
  static const int maxImageWidth = 1920;
  static const int maxImageHeight = 1920;
  static const int imageQuality = 85;
  static const int maxFileSize = 10 * 1024 * 1024; // 10MB

  /// Upload a single file with progress tracking
  static Future<UploadResult> uploadFile({
    required File file,
    required String endpoint,
    String fieldName = 'file',
    Map<String, dynamic>? metadata,
    void Function(double progress)? onProgress,
  }) async {
    try {
      // Validate file size
      final fileSize = await file.length();
      if (fileSize > maxFileSize) {
        throw UploadException('File size exceeds maximum limit of 10MB');
      }

      // Compress image if applicable
      File processedFile = file;
      final mimeType = lookupMimeType(file.path);
      if (mimeType?.startsWith('image/') ?? false) {
        processedFile = await _compressImage(file);
      }

      final formData = FormData.fromMap({
        fieldName: await MultipartFile.fromFile(
          processedFile.path,
          filename: file.path.split('/').last,
        ),
        if (metadata != null) ...metadata,
      });

      final response = await DioClient.dio.post(
        endpoint,
        data: formData,
        options: Options(
          contentType: 'multipart/form-data',
        ),
        onSendProgress: (sent, total) {
          if (total > 0 && onProgress != null) {
            onProgress(sent / total);
          }
        },
      );

      return UploadResult(
        success: true,
        url: response.data['data']['url'],
        fileId: response.data['data']['id'],
      );
    } on DioException catch (e) {
      throw UploadException(
        e.response?.data?['message'] ?? 'Upload failed',
        originalError: e,
      );
    }
  }

  /// Upload multiple files
  static Future<List<UploadResult>> uploadMultipleFiles({
    required List<File> files,
    required String endpoint,
    String fieldName = 'files',
    void Function(int current, int total, double progress)? onProgress,
  }) async {
    final results = <UploadResult>[];

    for (var i = 0; i < files.length; i++) {
      final result = await uploadFile(
        file: files[i],
        endpoint: endpoint,
        fieldName: fieldName,
        onProgress: (progress) {
          onProgress?.call(i + 1, files.length, progress);
        },
      );
      results.add(result);
    }

    return results;
  }

  /// Compress image to reduce file size
  static Future<File> _compressImage(File file) async {
    final dir = await getTemporaryDirectory();
    final targetPath = '${dir.path}/${DateTime.now().millisecondsSinceEpoch}.jpg';

    final result = await FlutterImageCompress.compressAndGetFile(
      file.absolute.path,
      targetPath,
      quality: imageQuality,
      maxWidth: maxImageWidth,
      maxHeight: maxImageHeight,
    );

    return File(result!.path);
  }

  /// Cancel ongoing upload
  static void cancelUpload(String cancelToken) {
    // Implementation using CancelToken
  }
}

class UploadResult {
  final bool success;
  final String? url;
  final String? fileId;
  final String? error;

  UploadResult({
    required this.success,
    this.url,
    this.fileId,
    this.error,
  });
}

class UploadException implements Exception {
  final String message;
  final dynamic originalError;

  UploadException(this.message, {this.originalError});

  @override
  String toString() => 'UploadException: $message';
}
```

### 9.2 Upload Progress Widget

```dart
// lib/core/widgets/upload_progress_widget.dart

import 'package:flutter/material.dart';

class UploadProgressWidget extends StatelessWidget {
  final double progress;
  final String? fileName;
  final VoidCallback? onCancel;

  const UploadProgressWidget({
    super.key,
    required this.progress,
    this.fileName,
    this.onCancel,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.grey.shade100,
        borderRadius: BorderRadius.circular(8),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (fileName != null) ...[
            Text(
              fileName!,
              style: const TextStyle(fontWeight: FontWeight.w500),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
            const SizedBox(height: 8),
          ],
          Row(
            children: [
              Expanded(
                child: LinearProgressIndicator(
                  value: progress,
                  backgroundColor: Colors.grey.shade300,
                ),
              ),
              const SizedBox(width: 12),
              Text(
                '${(progress * 100).toInt()}%',
                style: const TextStyle(fontSize: 12),
              ),
              if (onCancel != null) ...[
                const SizedBox(width: 8),
                IconButton(
                  icon: const Icon(Icons.close, size: 18),
                  onPressed: onCancel,
                  padding: EdgeInsets.zero,
                  constraints: const BoxConstraints(),
                ),
              ],
            ],
          ),
        ],
      ),
    );
  }
}
```

---

## 10. Caching Strategy

### 10.1 Cache Interceptor

```dart
// lib/core/network/interceptors/cache_interceptor.dart

import 'dart:convert';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';

class CacheInterceptor extends Interceptor {
  static final Map<String, _CacheEntry> _memoryCache = {};
  static SharedPreferences? _prefs;
  static const String _cacheKeyPrefix = 'api_cache_';

  static Future<void> initialize() async {
    _prefs ??= await SharedPreferences.getInstance();
  }

  @override
  Future<void> onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    // Skip if no-cache header is present
    if (options.headers['X-No-Cache'] == 'true') {
      return handler.next(options);
    }

    final cacheKey = _generateCacheKey(options);
    final maxAge = options.extra['cache_max_age'] as Duration?;

    if (maxAge != null && options.method.toUpperCase() == 'GET') {
      final cached = await _getFromCache(cacheKey, maxAge);
      if (cached != null) {
        return handler.resolve(
          Response(
            requestOptions: options,
            data: cached,
            statusCode: 200,
            headers: Headers.fromMap({
              'X-Cache': ['HIT'],
            }),
          ),
        );
      }
    }

    options.extra['cache_key'] = cacheKey;
    handler.next(options);
  }

  @override
  Future<void> onResponse(
    Response response,
    ResponseInterceptorHandler handler,
  ) async {
    final cacheKey = response.requestOptions.extra['cache_key'] as String?;
    final cacheMaxAge = response.requestOptions.extra['cache_max_age'] as Duration?;

    if (cacheKey != null &&
        cacheMaxAge != null &&
        response.statusCode == 200 &&
        response.requestOptions.method.toUpperCase() == 'GET') {
      await _saveToCache(cacheKey, response.data, cacheMaxAge);
    }

    handler.next(response);
  }

  static String _generateCacheKey(RequestOptions options) {
    return '${options.method}_${options.uri}';
  }

  static Future<dynamic> _getFromCache(String key, Duration maxAge) async {
    // Check memory cache first
    final memoryEntry = _memoryCache[key];
    if (memoryEntry != null && !memoryEntry.isExpired(maxAge)) {
      return memoryEntry.data;
    }

    // Check disk cache
    if (_prefs != null) {
      final cached = _prefs!.getString('$_cacheKeyPrefix$key');
      if (cached != null) {
        final entry = _CacheEntry.fromJson(jsonDecode(cached));
        if (!entry.isExpired(maxAge)) {
          // Update memory cache
          _memoryCache[key] = entry;
          return entry.data;
        }
      }
    }

    return null;
  }

  static Future<void> _saveToCache(
    String key,
    dynamic data,
    Duration maxAge,
  ) async {
    final entry = _CacheEntry(
      data: data,
      timestamp: DateTime.now(),
    );

    // Save to memory
    _memoryCache[key] = entry;

    // Save to disk
    if (_prefs != null) {
      await _prefs!.setString(
        '$_cacheKeyPrefix$key',
        jsonEncode(entry.toJson()),
      );
    }
  }

  static Future<void> clearCache() async {
    _memoryCache.clear();
    if (_prefs != null) {
      final keys = _prefs!.getKeys().where((k) => k.startsWith(_cacheKeyPrefix));
      for (final key in keys) {
        await _prefs!.remove(key);
      }
    }
  }

  static Future<void> invalidateCache(String pattern) async {
    // Remove from memory
    _memoryCache.removeWhere((key, _) => key.contains(pattern));

    // Remove from disk
    if (_prefs != null) {
      final keys = _prefs!
          .getKeys()
          .where((k) => k.startsWith(_cacheKeyPrefix) && k.contains(pattern));
      for (final key in keys) {
        await _prefs!.remove(key);
      }
    }
  }
}

class _CacheEntry {
  final dynamic data;
  final DateTime timestamp;

  _CacheEntry({
    required this.data,
    required this.timestamp,
  });

  bool isExpired(Duration maxAge) {
    return DateTime.now().difference(timestamp) > maxAge;
  }

  Map<String, dynamic> toJson() => {
        'data': data,
        'timestamp': timestamp.toIso8601String(),
      };

  factory _CacheEntry.fromJson(Map<String, dynamic> json) => _CacheEntry(
        data: json['data'],
        timestamp: DateTime.parse(json['timestamp']),
      );
}
```

### 10.2 TTL Policies

```dart
// lib/core/network/cache_policies.dart

import 'package:dio/dio.dart';

class CachePolicies {
  CachePolicies._();

  /// Short-term cache (1 minute) - for rapidly changing data
  static const Duration shortTerm = Duration(minutes: 1);

  /// Medium-term cache (5 minutes) - for semi-dynamic data
  static const Duration mediumTerm = Duration(minutes: 5);

  /// Long-term cache (30 minutes) - for relatively static data
  static const Duration longTerm = Duration(minutes: 30);

  /// Extended cache (1 hour) - for reference data
  static const Duration extended = Duration(hours: 1);

  /// Permanent cache - for lookup tables, enums, etc.
  static const Duration permanent = Duration(days: 7);
}

extension CacheOptions on RequestOptions {
  /// Add cache configuration to request
  RequestOptions withCache(Duration maxAge) {
    extra['cache_max_age'] = maxAge;
    return this;
  }

  /// Disable caching for this request
  RequestOptions noCache() {
    headers['X-No-Cache'] = 'true';
    return this;
  }
}
```

---

## 11. API Endpoints

### 11.1 Endpoint Definitions

```dart
// lib/core/network/api_endpoints.dart

class ApiEndpoints {
  ApiEndpoints._();

  // Base URLs
  static const String productionBaseUrl = 'https://api.smartdairybd.com/v1';
  static const String stagingBaseUrl = 'https://api-staging.smartdairybd.com/v1';

  // Auth
  static const String login = '/auth/login';
  static const String register = '/auth/register';
  static const String logout = '/auth/logout';
  static const String refreshToken = '/auth/refresh';
  static const String forgotPassword = '/auth/forgot-password';
  static const String resetPassword = '/auth/reset-password';
  static const String me = '/auth/me';
  static const String changePassword = '/auth/change-password';

  // Farms
  static const String farms = '/farms';
  static String farmById(String id) => '/farms/$id';
  static String farmStats(String id) => '/farms/$id/stats';
  static const String searchFarms = '/farms/search';

  // Animals
  static const String animals = '/animals';
  static String animalById(String id) => '/animals/$id';
  static String animalHealthRecords(String id) => '/animals/$id/health-records';
  static String animalMilkProduction(String id) => '/animals/$id/milk-production';
  static const String searchAnimals = '/animals/search';

  // Milk Production
  static const String milkProduction = '/milk-production';
  static String milkProductionById(String id) => '/milk-production/$id';
  static const String dailyMilkSummary = '/milk-production/daily-summary';
  static const String monthlyMilkReport = '/milk-production/monthly-report';

  // Health Records
  static const String healthRecords = '/health-records';
  static String healthRecordById(String id) => '/health-records/$id';

  // Feeding
  static const String feedingSchedules = '/feeding/schedules';
  static const String feedingRecords = '/feeding/records';
  static String feedingRecordById(String id) => '/feeding/records/$id';

  // Reports
  static const String reports = '/reports';
  static const String exportReport = '/reports/export';

  // Users
  static const String users = '/users';
  static String userById(String id) => '/users/$id';
  static const String userProfile = '/users/profile';

  // Settings
  static const String settings = '/settings';
  static const String notifications = '/settings/notifications';

  // Uploads
  static const String uploadImage = '/uploads/image';
  static const String uploadDocument = '/uploads/document';
  static const String uploadBulk = '/uploads/bulk';
}
```

### 11.2 Environment Configuration

```dart
// lib/core/config/environment.dart

enum Environment {
  development,
  staging,
  production,
}

class EnvironmentConfig {
  static Environment _current = Environment.production;

  static Environment get current => _current;

  static void setEnvironment(Environment env) {
    _current = env;
  }

  static String get baseUrl {
    switch (_current) {
      case Environment.development:
        return 'http://localhost:8000/v1';
      case Environment.staging:
        return 'https://api-staging.smartdairybd.com/v1';
      case Environment.production:
        return 'https://api.smartdairybd.com/v1';
    }
  }

  static bool get isDebug {
    switch (_current) {
      case Environment.development:
      case Environment.staging:
        return true;
      case Environment.production:
        return false;
    }
  }

  static bool get enableLogging => isDebug;
  static bool get enableCrashlytics => !isDebug;
}
```

---

## 12. Security

### 12.1 Certificate Pinning

```dart
// lib/core/security/certificate_pinning.dart

import 'dart:io';
import 'package:dio/dio.dart';
import 'package:dio/io.dart';

class CertificatePinning {
  static const List<String> _pinnedCertificates = [
    // SHA-256 hashes of certificate public keys
    'sha256/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=', // Production
    'sha256/BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB=', // Staging
  ];

  static void configure(Dio dio) {
    (dio.httpClientAdapter as IOHttpClientAdapter).onHttpClientCreate =
        (client) {
      client.badCertificateCallback = (cert, host, port) {
        // Implement certificate pinning logic
        return _validateCertificate(cert, host);
      };
      return client;
    };
  }

  static bool _validateCertificate(X509Certificate cert, String host) {
    // In production, implement proper certificate pinning
    // For now, return true for development
    // TODO: Implement proper pinning for production
    return true;
  }
}
```

### 12.2 SSL Handling

```dart
// lib/core/security/ssl_config.dart

import 'dart:io';
import 'package:dio/dio.dart';
import 'package:dio/io.dart';

class SslConfig {
  static void configure(Dio dio, {bool ignoreBadCertificate = false}) {
    (dio.httpClientAdapter as IOHttpClientAdapter).onHttpClientCreate =
        (HttpClient client) {
      client.badCertificateCallback =
          (X509Certificate cert, String host, int port) {
        if (ignoreBadCertificate) {
          // Only for development!
          return true;
        }
        // Implement proper SSL validation
        return _isValidCertificate(cert, host);
      };
      return client;
    };
  }

  static bool _isValidCertificate(X509Certificate cert, String host) {
    // Implement certificate validation logic
    // Check: certificate validity, hostname matching, etc.
    return true;
  }
}
```

---

## 13. Testing

### 13.1 Mock Dio Client

```dart
// test/mocks/mock_dio_client.dart

import 'package:dio/dio.dart';
import 'package:mockito/annotations.dart';
import 'package:mockito/mockito.dart';

@GenerateMocks([Dio])
import 'mock_dio_client.mocks.dart';

class MockDioClient {
  static MockDio createMockDio() {
    return MockDio();
  }

  static void mockGet(
    MockDio mockDio, {
    required String path,
    required dynamic responseData,
    int statusCode = 200,
  }) {
    when(mockDio.get(
      path,
      data: anyNamed('data'),
      queryParameters: anyNamed('queryParameters'),
      options: anyNamed('options'),
      cancelToken: anyNamed('cancelToken'),
      onReceiveProgress: anyNamed('onReceiveProgress'),
    )).thenAnswer(
      (_) async => Response(
        data: responseData,
        statusCode: statusCode,
        requestOptions: RequestOptions(path: path),
      ),
    );
  }

  static void mockPost(
    MockDio mockDio, {
    required String path,
    required dynamic responseData,
    int statusCode = 200,
  }) {
    when(mockDio.post(
      path,
      data: anyNamed('data'),
      queryParameters: anyNamed('queryParameters'),
      options: anyNamed('options'),
      cancelToken: anyNamed('cancelToken'),
      onSendProgress: anyNamed('onSendProgress'),
      onReceiveProgress: anyNamed('onReceiveProgress'),
    )).thenAnswer(
      (_) async => Response(
        data: responseData,
        statusCode: statusCode,
        requestOptions: RequestOptions(path: path),
      ),
    );
  }

  static void mockError(
    MockDio mockDio, {
    required String path,
    required DioExceptionType type,
    int? statusCode,
    dynamic errorData,
  }) {
    when(mockDio.get(
      path,
      data: anyNamed('data'),
      queryParameters: anyNamed('queryParameters'),
      options: anyNamed('options'),
    )).thenThrow(
      DioException(
        type: type,
        requestOptions: RequestOptions(path: path),
        response: statusCode != null
            ? Response(
                statusCode: statusCode,
                data: errorData,
                requestOptions: RequestOptions(path: path),
              )
            : null,
      ),
    );
  }
}
```

### 13.2 Repository Unit Tests

```dart
// test/features/farm/data/repositories/farm_repository_impl_test.dart

import 'package:dartz/dartz.dart';
import 'package:dio/dio.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:mockito/mockito.dart';
import 'package:smart_dairy/core/errors/failures.dart';
import 'package:smart_dairy/features/farm/data/datasources/farm_remote_data_source.dart';
import 'package:smart_dairy/features/farm/data/models/farm_model.dart';
import 'package:smart_dairy/features/farm/data/repositories/farm_repository_impl.dart';

import '../../../mocks/mock_dio_client.mocks.dart';

class MockFarmRemoteDataSource extends Mock implements FarmRemoteDataSource {}

void main() {
  late FarmRepositoryImpl repository;
  late MockFarmRemoteDataSource mockDataSource;

  setUp(() {
    mockDataSource = MockFarmRemoteDataSource();
    repository = FarmRepositoryImpl(remoteDataSource: mockDataSource);
  });

  group('getFarms', () {
    final tFarmModels = [
      FarmModel(
        id: '1',
        name: 'Test Farm',
        ownerId: 'user1',
        totalAnimals: 10,
        dailyProduction: 50.0,
        address: const FarmAddressModel(
          street: '123 Farm Road',
          city: 'Dhaka',
          district: 'Dhaka',
          country: 'Bangladesh',
        ),
        createdAt: DateTime.now(),
        updatedAt: DateTime.now(),
      ),
    ];

    test('should return list of farms when data source succeeds', () async {
      // Arrange
      when(mockDataSource.getFarms(page: anyNamed('page'), limit: anyNamed('limit')))
          .thenAnswer((_) async => tFarmModels);

      // Act
      final result = await repository.getFarms();

      // Assert
      expect(result.isRight(), true);
      result.fold(
        (failure) => fail('Should not return failure'),
        (farms) => expect(farms.length, 1),
      );
    });

    test('should return ServerFailure when data source throws DioException',
        () async {
      // Arrange
      when(mockDataSource.getFarms(page: anyNamed('page'), limit: anyNamed('limit')))
          .thenThrow(
        DioException(
          type: DioExceptionType.connectionTimeout,
          requestOptions: RequestOptions(path: '/farms'),
        ),
      );

      // Act
      final result = await repository.getFarms();

      // Assert
      expect(result.isLeft(), true);
      result.fold(
        (failure) => expect(failure, isA<ServerFailure>()),
        (_) => fail('Should not return success'),
      );
    });
  });

  group('createFarm', () {
    final tCreateRequest = CreateFarmRequest(
      name: 'New Farm',
      address: FarmAddressRequest(
        street: '456 New Road',
        city: 'Chittagong',
        district: 'Chittagong',
      ),
    );

    final tCreatedFarm = FarmModel(
      id: '2',
      name: 'New Farm',
      ownerId: 'user1',
      totalAnimals: 0,
      dailyProduction: 0.0,
      address: FarmAddressModel(
        street: '456 New Road',
        city: 'Chittagong',
        district: 'Chittagong',
        country: 'Bangladesh',
      ),
      createdAt: DateTime.now(),
      updatedAt: DateTime.now(),
    );

    test('should return created farm when data source succeeds', () async {
      // Arrange
      when(mockDataSource.createFarm(tCreateRequest))
          .thenAnswer((_) async => tCreatedFarm);

      // Act
      final result = await repository.createFarm(tCreateRequest);

      // Assert
      expect(result.isRight(), true);
      result.fold(
        (failure) => fail('Should not return failure'),
        (farm) => expect(farm.name, 'New Farm'),
      );
    });
  });
}
```

### 13.3 Widget Tests with Mock APIs

```dart
// test/features/farm/presentation/widgets/farm_list_test.dart

import 'package:bloc_test/bloc_test.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:mocktail/mocktail.dart';
import 'package:smart_dairy/features/farm/domain/entities/farm.dart';
import 'package:smart_dairy/features/farm/presentation/bloc/farm_bloc.dart';
import 'package:smart_dairy/features/farm/presentation/bloc/farm_state.dart';
import 'package:smart_dairy/features/farm/presentation/widgets/farm_list.dart';

class MockFarmBloc extends MockBloc<FarmEvent, FarmState> implements FarmBloc {}

void main() {
  late MockFarmBloc mockFarmBloc;

  setUp(() {
    mockFarmBloc = MockFarmBloc();
  });

  Widget buildTestableWidget(Widget child) {
    return MaterialApp(
      home: BlocProvider<FarmBloc>.value(
        value: mockFarmBloc,
        child: Scaffold(body: child),
      ),
    );
  }

  testWidgets('should display loading indicator when state is loading',
      (WidgetTester tester) async {
    // Arrange
    when(() => mockFarmBloc.state).thenReturn(FarmLoading());

    // Act
    await tester.pumpWidget(buildTestableWidget(const FarmList()));

    // Assert
    expect(find.byType(CircularProgressIndicator), findsOneWidget);
  });

  testWidgets('should display list of farms when state is loaded',
      (WidgetTester tester) async {
    // Arrange
    final farms = [
      Farm(
        id: '1',
        name: 'Farm 1',
        ownerId: 'user1',
        totalAnimals: 10,
        dailyProduction: 50.0,
        address: FarmAddress(
          street: 'Street 1',
          city: 'Dhaka',
          district: 'Dhaka',
          country: 'Bangladesh',
        ),
        createdAt: DateTime.now(),
        updatedAt: DateTime.now(),
      ),
    ];
    when(() => mockFarmBloc.state).thenReturn(FarmLoaded(farms));

    // Act
    await tester.pumpWidget(buildTestableWidget(const FarmList()));

    // Assert
    expect(find.text('Farm 1'), findsOneWidget);
    expect(find.text('10 animals'), findsOneWidget);
  });

  testWidgets('should display error message when state is error',
      (WidgetTester tester) async {
    // Arrange
    when(() => mockFarmBloc.state)
        .thenReturn(const FarmError('Failed to load farms'));

    // Act
    await tester.pumpWidget(buildTestableWidget(const FarmList()));

    // Assert
    expect(find.text('Failed to load farms'), findsOneWidget);
    expect(find.text('Retry'), findsOneWidget);
  });
}
```

---

## 14. Performance

### 14.1 Request Batching

```dart
// lib/core/network/batch_request.dart

import 'dart:async';
import 'package:dio/dio.dart';
import 'dio_client.dart';

class BatchRequestManager {
  static final Map<String, _BatchEntry> _pendingBatches = {};
  static const Duration batchWindow = Duration(milliseconds: 50);

  static Future<Response> addToBatch({
    required String batchKey,
    required String method,
    required String path,
    dynamic data,
    Map<String, dynamic>? queryParameters,
  }) {
    final completer = Completer<Response>();

    if (!_pendingBatches.containsKey(batchKey)) {
      _pendingBatches[batchKey] = _BatchEntry(
        requests: [],
        timer: Timer(batchWindow, () => _executeBatch(batchKey)),
      );
    }

    _pendingBatches[batchKey]!.requests.add(_BatchRequest(
      method: method,
      path: path,
      data: data,
      queryParameters: queryParameters,
      completer: completer,
    ));

    return completer.future;
  }

  static Future<void> _executeBatch(String batchKey) async {
    final entry = _pendingBatches.remove(batchKey);
    if (entry == null || entry.requests.isEmpty) return;

    try {
      // Send batch request
      final response = await DioClient.dio.post(
        '/batch',
        data: {
          'requests': entry.requests.map((r) => r.toJson()).toList(),
        },
      );

      // Distribute responses
      final results = response.data['data'] as List;
      for (var i = 0; i < entry.requests.length; i++) {
        entry.requests[i].completer.complete(
          Response(
            data: results[i],
            statusCode: 200,
            requestOptions: RequestOptions(path: entry.requests[i].path),
          ),
        );
      }
    } catch (e) {
      // Complete all with error
      for (var request in entry.requests) {
        request.completer.completeError(e);
      }
    }
  }
}

class _BatchEntry {
  final List<_BatchRequest> requests;
  final Timer timer;

  _BatchEntry({required this.requests, required this.timer});
}

class _BatchRequest {
  final String method;
  final String path;
  final dynamic data;
  final Map<String, dynamic>? queryParameters;
  final Completer<Response> completer;

  _BatchRequest({
    required this.method,
    required this.path,
    this.data,
    this.queryParameters,
    required this.completer,
  });

  Map<String, dynamic> toJson() => {
        'method': method,
        'path': path,
        if (data != null) 'body': data,
        if (queryParameters != null) 'query': queryParameters,
      };
}
```

### 14.2 Debouncing

```dart
// lib/core/utils/debouncer.dart

import 'dart:async';

class Debouncer {
  final Duration delay;
  Timer? _timer;

  Debouncer({required this.delay});

  void run(VoidCallback action) {
    _timer?.cancel();
    _timer = Timer(delay, action);
  }

  void cancel() {
    _timer?.cancel();
  }
}

// Usage in search
class SearchDebouncer {
  static final Debouncer _debouncer = Debouncer(
    delay: const Duration(milliseconds: 500),
  );

  static void debounceSearch(
    String query,
    void Function(String) onSearch,
  ) {
    _debouncer.run(() => onSearch(query));
  }
}
```

---

## 15. Logging

### 15.1 Request/Response Logging

```dart
// lib/core/logging/api_logger.dart

import 'dart:convert';
import 'package:dio/dio.dart';
import 'package:logger/logger.dart';

class ApiLogger {
  static final Logger _logger = Logger(
    printer: PrettyPrinter(
      methodCount: 2,
      errorMethodCount: 8,
      lineLength: 120,
      colors: true,
      printEmojis: true,
      dateTimeFormat: DateTimeFormat.dateAndTime,
    ),
  );

  static void logRequest(RequestOptions options) {
    _logger.t(
      'API Request: ${options.method} ${options.path}',
      error: {
        'headers': _sanitizeHeaders(options.headers),
        'queryParams': options.queryParameters,
        'body': options.data,
      },
    );
  }

  static void logResponse(Response response) {
    _logger.d(
      'API Response: ${response.statusCode} ${response.requestOptions.path}',
      error: {
        'data': response.data,
      },
    );
  }

  static void logError(DioException error) {
    _logger.e(
      'API Error: ${error.type} ${error.requestOptions.path}',
      error: {
        'statusCode': error.response?.statusCode,
        'errorData': error.response?.data,
        'message': error.message,
      },
      stackTrace: error.stackTrace,
    );
  }

  static Map<String, dynamic> _sanitizeHeaders(Map<String, dynamic> headers) {
    final sanitized = Map<String, dynamic>.from(headers);
    if (sanitized.containsKey('authorization')) {
      sanitized['authorization'] = '***REDACTED***';
    }
    return sanitized;
  }
}
```

### 15.2 Analytics Logging

```dart
// lib/core/analytics/api_analytics.dart

import 'package:dio/dio.dart';

class ApiAnalytics {
  static final List<ApiMetric> _metrics = [];
  static const int maxMetrics = 100;

  static void trackRequest(RequestOptions options) {
    _addMetric(ApiMetric(
      path: options.path,
      method: options.method,
      startTime: DateTime.now(),
    ));
  }

  static void trackResponse(Response response) {
    final metric = _metrics.lastWhere(
      (m) => m.path == response.requestOptions.path,
      orElse: () => ApiMetric.empty(),
    );
    metric.endTime = DateTime.now();
    metric.statusCode = response.statusCode;
  }

  static void trackError(DioException error) {
    final metric = _metrics.lastWhere(
      (m) => m.path == error.requestOptions.path,
      orElse: () => ApiMetric.empty(),
    );
    metric.endTime = DateTime.now();
    metric.error = error.message;
  }

  static void _addMetric(ApiMetric metric) {
    _metrics.add(metric);
    if (_metrics.length > maxMetrics) {
      _metrics.removeAt(0);
    }
  }

  static List<ApiMetric> getMetrics() => List.unmodifiable(_metrics);

  static double getAverageResponseTime() {
    if (_metrics.isEmpty) return 0;
    final completed = _metrics.where((m) => m.duration != null);
    if (completed.isEmpty) return 0;
    return completed.map((m) => m.duration!.inMilliseconds).reduce((a, b) => a + b) /
        completed.length;
  }

  static void clearMetrics() => _metrics.clear();
}

class ApiMetric {
  final String path;
  final String method;
  final DateTime startTime;
  DateTime? endTime;
  int? statusCode;
  String? error;

  ApiMetric({
    required this.path,
    required this.method,
    required this.startTime,
    this.endTime,
    this.statusCode,
    this.error,
  });

  ApiMetric.empty()
      : path = '',
        method = '',
        startTime = DateTime.now();

  Duration? get duration =>
      endTime != null ? endTime!.difference(startTime) : null;

  bool get isSuccess => statusCode != null && statusCode! >= 200 && statusCode! < 300;
}
```

---

## 16. Appendices

### Appendix A: Complete Dio Client Setup

```dart
// lib/core/network/dio_client_complete.dart

import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import '../config/environment.dart';
import 'interceptors/auth_interceptor.dart';
import 'interceptors/cache_interceptor.dart';
import 'interceptors/logging_interceptor.dart';
import 'interceptors/retry_interceptor.dart';

class DioClientComplete {
  static Dio? _dio;
  static final List<Interceptor> _customInterceptors = [];

  static Dio get instance {
    _dio ??= _createDio();
    return _dio!;
  }

  static Dio _createDio() {
    final dio = Dio(
      BaseOptions(
        baseUrl: EnvironmentConfig.baseUrl,
        connectTimeout: const Duration(seconds: 30),
        receiveTimeout: const Duration(seconds: 30),
        sendTimeout: const Duration(seconds: 30),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-App-Version': '1.0.0',
          'X-Platform': defaultTargetPlatform.name,
          'X-Device-ID': 'device-id-here', // Get from device info
        },
        validateStatus: (status) => status != null && status < 500,
      ),
    );

    // Add interceptors in specific order
    final interceptors = [
      LoggingInterceptor(),
      AuthInterceptor(),
      RetryInterceptor(dio: dio, retries: 3),
      CacheInterceptor(),
      ..._customInterceptors,
    ];

    dio.interceptors.addAll(interceptors);

    // Configure SSL
    _configureSsl(dio);

    return dio;
  }

  static void _configureSsl(Dio dio) {
    // SSL configuration based on environment
    if (EnvironmentConfig.current == Environment.development) {
      // Allow bad certificates in development
    }
  }

  static void addInterceptor(Interceptor interceptor) {
    _customInterceptors.add(interceptor);
    _dio?.interceptors.add(interceptor);
  }

  static void removeInterceptor(Interceptor interceptor) {
    _customInterceptors.remove(interceptor);
    _dio?.interceptors.remove(interceptor);
  }

  static void updateBaseUrl(String baseUrl) {
    instance.options.baseUrl = baseUrl;
  }

  static void setAuthToken(String token) {
    instance.options.headers['Authorization'] = 'Bearer $token';
  }

  static void clearAuthToken() {
    instance.options.headers.remove('Authorization');
  }

  static void reset() {
    _dio = null;
    _customInterceptors.clear();
  }
}
```

### Appendix B: Troubleshooting Guide

#### Common Issues and Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| **Connection Timeout** | Slow network, server unresponsive | Increase timeout, implement retry logic |
| **401 Unauthorized** | Token expired, invalid credentials | Refresh token, re-authenticate user |
| **Certificate Error** | SSL pinning mismatch, expired cert | Update pinned certificates, check SSL config |
| **Slow Image Upload** | Large file size, no compression | Implement image compression before upload |
| **Memory Leak** | Dio instances not disposed | Use singleton pattern, properly cancel requests |
| **Cache Not Working** | Missing cache headers, TTL expired | Check cache interceptor config, verify TTL |

#### Debug Checklist

```dart
// lib/core/debug/api_debug.dart

class ApiDebug {
  static void printConfig() {
    final dio = DioClientComplete.instance;
    
    debugPrint('=== API Configuration ===');
    debugPrint('Base URL: ${dio.options.baseUrl}');
    debugPrint('Connect Timeout: ${dio.options.connectTimeout}');
    debugPrint('Receive Timeout: ${dio.options.receiveTimeout}');
    debugPrint('Headers: ${dio.options.headers}');
    debugPrint('Interceptors: ${dio.interceptors.map((i) => i.runtimeType).join(', ')}');
    debugPrint('========================');
  }

  static void testConnection() async {
    try {
      final dio = DioClientComplete.instance;
      final response = await dio.get('/health');
      debugPrint('Connection Test: SUCCESS (${response.statusCode})');
    } catch (e) {
      debugPrint('Connection Test: FAILED ($e)');
    }
  }

  static void verifyAuth() async {
    final token = await TokenManager.getAccessToken();
    debugPrint('Auth Token: ${token != null ? 'Present' : 'Missing'}');
    if (token != null) {
      final isValid = await TokenManager.hasValidToken();
      debugPrint('Token Valid: $isValid');
    }
  }
}
```

### Appendix C: Migration Guide

#### From HTTP to Dio

```dart
// Before (using http package)
import 'package:http/http.dart' as http;

Future<void> oldWay() async {
  final response = await http.get(Uri.parse('$baseUrl/farms'));
  if (response.statusCode == 200) {
    // Handle response
  }
}

// After (using Dio)
Future<void> newWay() async {
  final response = await DioClient.dio.get('/farms');
  // Dio handles errors automatically
}
```

#### API Version Migration

```dart
// Version upgrade helper
class ApiVersionMigration {
  static void handleVersionChange(String oldVersion, String newVersion) {
    // Clear cache on major version change
    if (_isMajorVersionChange(oldVersion, newVersion)) {
      CacheInterceptor.clearCache();
    }
  }

  static bool _isMajorVersionChange(String oldV, String newV) {
    final oldParts = oldV.split('.').map(int.parse).toList();
    final newParts = newV.split('.').map(int.parse).toList();
    return oldParts[0] != newParts[0];
  }
}
```

---

## Document Control

### Distribution List

| Role | Name | Department |
|------|------|------------|
| Mobile Lead | [Name] | Engineering |
| Backend Lead | [Name] | Engineering |
| QA Lead | [Name] | Quality Assurance |
| Tech Lead | [Name] | Engineering |

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Document Owner | | | |
| Reviewer | | | |
| Approver | | | |

---

*This document is the property of Smart Dairy Ltd. Unauthorized distribution or copying is prohibited.*

**© 2026 Smart Dairy Ltd. All rights reserved.**
