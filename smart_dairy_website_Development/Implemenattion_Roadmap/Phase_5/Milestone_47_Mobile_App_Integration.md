# Milestone 47: Mobile App Integration
## Smart Dairy Digital Smart Portal + ERP - Phase 5

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P5-M47-001 |
| **Milestone** | 47 of 50 (7 of 10 in Phase 5) |
| **Title** | Mobile App Integration |
| **Phase** | Phase 5 - Farm Management Foundation |
| **Days** | Days 311-320 (of 350) |
| **Version** | 1.0 |
| **Status** | Draft |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Develop a comprehensive Flutter mobile application with offline-first architecture, REST API integration, RFID/barcode scanning, Bangla voice input, and seamless data synchronization for field operations.

### 1.2 Objectives

| # | Objective |
|---|-----------|
| 1 | Design and implement REST API framework for mobile |
| 2 | Build JWT authentication with refresh token mechanism |
| 3 | Implement offline-first architecture with Drift/Hive |
| 4 | Create animal management module for mobile |
| 5 | Develop health record entry screens |
| 6 | Build production data entry with quick forms |
| 7 | Implement RFID/barcode scanning (camera + NFC) |
| 8 | Integrate Bangla voice input for data entry |
| 9 | Build photo capture with GPS geotagging |
| 10 | Implement background sync and conflict resolution |

### 1.3 Key Deliverables

| Deliverable | Type | Description |
|-------------|------|-------------|
| Mobile REST API | Backend | Comprehensive API endpoints |
| Flutter App Core | Mobile | Base architecture, navigation |
| Offline Database | Mobile | Drift/Hive local storage |
| Sync Engine | Mobile | Background sync with conflict resolution |
| Scanner Module | Mobile | RFID/barcode/QR scanning |
| Voice Input | Mobile | Bangla speech-to-text |
| Media Module | Mobile | Photo capture with GPS |

### 1.4 Success Criteria

| Criteria | Target |
|----------|--------|
| Offline functionality | Full CRUD operations |
| Sync success rate | >99% |
| API response time | <500ms |
| Scanner accuracy | >95% |
| Voice recognition accuracy | >85% (Bangla) |
| App startup time | <3 seconds |

---

## 2. Requirement Traceability Matrix

| Req ID | Source | Description | Day(s) | Status |
|--------|--------|-------------|--------|--------|
| RFP-FARM-016 | RFP | Mobile data entry (offline) | 313-316 | Planned |
| RFP-FARM-017 | RFP | Bangla voice input | 318 | Planned |
| BRD-FARM-060 | BRD | RFID/barcode scanning | 317 | Planned |
| BRD-FARM-061 | BRD | Photo capture with GPS | 319 | Planned |
| SRS-FARM-100 | SRS | JWT authentication | 312 | Planned |
| SRS-FARM-101 | SRS | Offline sync mechanism | 313 | Planned |

---

## 3. Day-by-Day Implementation

### Day 311: API Framework & Architecture

**Theme:** REST API Foundation

#### Dev 1 - Backend Lead (8h)

**Task 1: API Gateway Setup (4h)**
```python
# farm_mobile_api/controllers/api_gateway.py
from odoo import http
from odoo.http import request, Response
import json
import functools
import logging

_logger = logging.getLogger(__name__)

def api_response(func):
    """Decorator for standardized API responses"""
    @functools.wraps(func)
    def wrapper(*args, **kwargs):
        try:
            result = func(*args, **kwargs)
            return {
                'success': True,
                'data': result,
                'error': None,
                'timestamp': fields.Datetime.now().isoformat(),
            }
        except Exception as e:
            _logger.exception(f"API Error in {func.__name__}")
            return {
                'success': False,
                'data': None,
                'error': {
                    'code': getattr(e, 'code', 'INTERNAL_ERROR'),
                    'message': str(e),
                },
                'timestamp': fields.Datetime.now().isoformat(),
            }
    return wrapper

def require_api_auth(func):
    """Decorator for JWT authentication"""
    @functools.wraps(func)
    def wrapper(*args, **kwargs):
        auth_header = request.httprequest.headers.get('Authorization')
        if not auth_header or not auth_header.startswith('Bearer '):
            return Response(
                json.dumps({'error': 'Missing or invalid authorization'}),
                status=401,
                content_type='application/json'
            )

        token = auth_header.split(' ')[1]
        try:
            payload = request.env['farm.api.token'].verify_token(token)
            request.uid = payload['user_id']
        except Exception as e:
            return Response(
                json.dumps({'error': 'Invalid or expired token'}),
                status=401,
                content_type='application/json'
            )

        return func(*args, **kwargs)
    return wrapper


class FarmMobileAPIGateway(http.Controller):

    @http.route('/api/v1/health', type='json', auth='none', methods=['GET'], csrf=False)
    def health_check(self):
        """API health check endpoint"""
        return {
            'status': 'healthy',
            'version': '1.0.0',
            'database': request.env.cr.dbname,
        }

    @http.route('/api/v1/sync/metadata', type='json', auth='user', methods=['GET'])
    @api_response
    def get_sync_metadata(self, **kwargs):
        """Get metadata for mobile sync"""
        return {
            'server_time': fields.Datetime.now().isoformat(),
            'schema_version': '1.0',
            'models': {
                'farm.animal': {
                    'last_modified': self._get_last_modified('farm.animal'),
                    'count': request.env['farm.animal'].search_count([]),
                },
                'farm.health.record': {
                    'last_modified': self._get_last_modified('farm.health.record'),
                    'count': request.env['farm.health.record'].search_count([]),
                },
                'farm.milk.production': {
                    'last_modified': self._get_last_modified('farm.milk.production'),
                    'count': request.env['farm.milk.production'].search_count([]),
                },
            }
        }

    def _get_last_modified(self, model_name):
        record = request.env[model_name].search([], order='write_date desc', limit=1)
        return record.write_date.isoformat() if record else None
```

**Task 2: API Versioning & Rate Limiting (4h)**
```python
# farm_mobile_api/models/api_config.py
class FarmAPIConfig(models.Model):
    _name = 'farm.api.config'
    _description = 'API Configuration'

    name = fields.Char(required=True)
    version = fields.Char(string='API Version', default='v1')
    rate_limit_per_minute = fields.Integer(default=60)
    rate_limit_per_hour = fields.Integer(default=1000)
    is_active = fields.Boolean(default=True)

    # Rate limiting with Redis
    def check_rate_limit(self, user_id, endpoint):
        import redis
        client = redis.Redis(host='localhost', port=6379, db=2)

        minute_key = f"rate:{user_id}:{endpoint}:minute"
        hour_key = f"rate:{user_id}:{endpoint}:hour"

        # Check minute limit
        minute_count = client.incr(minute_key)
        if minute_count == 1:
            client.expire(minute_key, 60)
        if minute_count > self.rate_limit_per_minute:
            raise Exception('Rate limit exceeded (per minute)')

        # Check hour limit
        hour_count = client.incr(hour_key)
        if hour_count == 1:
            client.expire(hour_key, 3600)
        if hour_count > self.rate_limit_per_hour:
            raise Exception('Rate limit exceeded (per hour)')

        return True


class FarmAPILog(models.Model):
    _name = 'farm.api.log'
    _description = 'API Request Log'
    _order = 'create_date desc'

    endpoint = fields.Char(required=True, index=True)
    method = fields.Char()
    user_id = fields.Many2one('res.users')
    request_body = fields.Text()
    response_status = fields.Integer()
    response_time_ms = fields.Integer()
    ip_address = fields.Char()
    user_agent = fields.Char()

    @api.model
    def log_request(self, endpoint, method, user_id, status, response_time, request_data=None):
        return self.create({
            'endpoint': endpoint,
            'method': method,
            'user_id': user_id,
            'response_status': status,
            'response_time_ms': response_time,
            'request_body': json.dumps(request_data) if request_data else None,
            'ip_address': request.httprequest.remote_addr,
            'user_agent': request.httprequest.user_agent.string,
        })
```

#### Dev 2 - Full-Stack (8h)

**Task 1: API Documentation with OpenAPI (4h)**
```python
# farm_mobile_api/controllers/openapi_spec.py
from odoo import http
from odoo.http import request
import json

class OpenAPIController(http.Controller):

    @http.route('/api/v1/openapi.json', type='http', auth='none', methods=['GET'], csrf=False)
    def openapi_spec(self):
        """Generate OpenAPI 3.0 specification"""
        spec = {
            'openapi': '3.0.0',
            'info': {
                'title': 'Smart Dairy Farm Mobile API',
                'version': '1.0.0',
                'description': 'REST API for Smart Dairy mobile application',
            },
            'servers': [
                {'url': '/api/v1', 'description': 'Production API'}
            ],
            'paths': {
                '/auth/login': {
                    'post': {
                        'summary': 'User login',
                        'requestBody': {
                            'content': {
                                'application/json': {
                                    'schema': {
                                        'type': 'object',
                                        'properties': {
                                            'username': {'type': 'string'},
                                            'password': {'type': 'string'},
                                        },
                                        'required': ['username', 'password']
                                    }
                                }
                            }
                        },
                        'responses': {
                            '200': {
                                'description': 'Login successful',
                                'content': {
                                    'application/json': {
                                        'schema': {'$ref': '#/components/schemas/AuthResponse'}
                                    }
                                }
                            }
                        }
                    }
                },
                '/animals': {
                    'get': {
                        'summary': 'List animals',
                        'security': [{'bearerAuth': []}],
                        'parameters': [
                            {'name': 'limit', 'in': 'query', 'schema': {'type': 'integer'}},
                            {'name': 'offset', 'in': 'query', 'schema': {'type': 'integer'}},
                            {'name': 'modified_since', 'in': 'query', 'schema': {'type': 'string', 'format': 'date-time'}},
                        ],
                        'responses': {
                            '200': {'description': 'List of animals'}
                        }
                    }
                },
                # Additional endpoints...
            },
            'components': {
                'securitySchemes': {
                    'bearerAuth': {
                        'type': 'http',
                        'scheme': 'bearer',
                        'bearerFormat': 'JWT'
                    }
                },
                'schemas': {
                    'AuthResponse': {
                        'type': 'object',
                        'properties': {
                            'access_token': {'type': 'string'},
                            'refresh_token': {'type': 'string'},
                            'expires_in': {'type': 'integer'},
                            'user': {'$ref': '#/components/schemas/User'}
                        }
                    },
                    'Animal': {
                        'type': 'object',
                        'properties': {
                            'id': {'type': 'integer'},
                            'name': {'type': 'string'},
                            'ear_tag': {'type': 'string'},
                            'rfid_tag': {'type': 'string'},
                            'species': {'type': 'string'},
                            'breed': {'type': 'string'},
                            'status': {'type': 'string'},
                        }
                    }
                }
            }
        }

        return request.make_response(
            json.dumps(spec, indent=2),
            headers={'Content-Type': 'application/json'}
        )
```

**Task 2: API Testing Framework (4h)**
```python
# farm_mobile_api/tests/test_api.py
from odoo.tests.common import HttpCase
import json

class TestMobileAPI(HttpCase):

    def setUp(self):
        super().setUp()
        self.api_base = '/api/v1'

    def test_health_check(self):
        response = self.url_open(f'{self.api_base}/health')
        self.assertEqual(response.status_code, 200)
        data = json.loads(response.content)
        self.assertEqual(data['status'], 'healthy')

    def test_login_success(self):
        response = self.url_open(
            f'{self.api_base}/auth/login',
            data=json.dumps({'username': 'admin', 'password': 'admin'}),
            headers={'Content-Type': 'application/json'}
        )
        self.assertEqual(response.status_code, 200)
        data = json.loads(response.content)
        self.assertIn('access_token', data['data'])

    def test_unauthorized_access(self):
        response = self.url_open(f'{self.api_base}/animals')
        self.assertEqual(response.status_code, 401)

    def test_animals_list_with_auth(self):
        # First login
        login_resp = self.url_open(
            f'{self.api_base}/auth/login',
            data=json.dumps({'username': 'admin', 'password': 'admin'}),
            headers={'Content-Type': 'application/json'}
        )
        token = json.loads(login_resp.content)['data']['access_token']

        # Then fetch animals
        response = self.url_open(
            f'{self.api_base}/animals',
            headers={'Authorization': f'Bearer {token}'}
        )
        self.assertEqual(response.status_code, 200)
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Flutter Project Architecture (5h)**
```dart
// lib/core/app.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:smart_dairy/core/di/injection.dart';
import 'package:smart_dairy/core/router/app_router.dart';
import 'package:smart_dairy/core/theme/app_theme.dart';

class SmartDairyApp extends StatelessWidget {
  const SmartDairyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider(create: (_) => getIt<AuthBloc>()),
        BlocProvider(create: (_) => getIt<SyncBloc>()),
        BlocProvider(create: (_) => getIt<ConnectivityBloc>()),
      ],
      child: MaterialApp.router(
        title: 'Smart Dairy',
        theme: AppTheme.lightTheme,
        darkTheme: AppTheme.darkTheme,
        routerConfig: AppRouter.router,
        localizationsDelegates: const [
          AppLocalizations.delegate,
          GlobalMaterialLocalizations.delegate,
          GlobalWidgetsLocalizations.delegate,
        ],
        supportedLocales: const [
          Locale('en'),
          Locale('bn'), // Bangla
        ],
      ),
    );
  }
}

// lib/core/di/injection.dart
import 'package:get_it/get_it.dart';
import 'package:injectable/injectable.dart';

final getIt = GetIt.instance;

@InjectableInit()
void configureDependencies() => getIt.init();

// lib/core/router/app_router.dart
import 'package:go_router/go_router.dart';

class AppRouter {
  static final router = GoRouter(
    initialLocation: '/splash',
    routes: [
      GoRoute(path: '/splash', builder: (_, __) => const SplashScreen()),
      GoRoute(path: '/login', builder: (_, __) => const LoginScreen()),
      ShellRoute(
        builder: (_, __, child) => MainShell(child: child),
        routes: [
          GoRoute(path: '/dashboard', builder: (_, __) => const DashboardScreen()),
          GoRoute(path: '/animals', builder: (_, __) => const AnimalsScreen()),
          GoRoute(path: '/animals/:id', builder: (_, state) =>
            AnimalDetailScreen(id: int.parse(state.pathParameters['id']!))),
          GoRoute(path: '/production', builder: (_, __) => const ProductionScreen()),
          GoRoute(path: '/health', builder: (_, __) => const HealthScreen()),
          GoRoute(path: '/scanner', builder: (_, __) => const ScannerScreen()),
        ],
      ),
    ],
    redirect: (context, state) {
      final authState = context.read<AuthBloc>().state;
      final isLoggedIn = authState is AuthAuthenticated;
      final isLoggingIn = state.matchedLocation == '/login';

      if (!isLoggedIn && !isLoggingIn && state.matchedLocation != '/splash') {
        return '/login';
      }
      return null;
    },
  );
}
```

**Task 2: API Client Service (3h)**
```dart
// lib/core/network/api_client.dart
import 'package:dio/dio.dart';
import 'package:injectable/injectable.dart';
import 'package:smart_dairy/core/storage/secure_storage.dart';

@lazySingleton
class ApiClient {
  final Dio _dio;
  final SecureStorage _storage;

  ApiClient(this._storage) : _dio = Dio() {
    _dio.options.baseUrl = 'https://api.smartdairy.com/api/v1';
    _dio.options.connectTimeout = const Duration(seconds: 30);
    _dio.options.receiveTimeout = const Duration(seconds: 30);

    _dio.interceptors.addAll([
      _AuthInterceptor(_storage, _dio),
      _LoggingInterceptor(),
      _ErrorInterceptor(),
    ]);
  }

  Future<Response<T>> get<T>(String path, {Map<String, dynamic>? queryParameters}) =>
    _dio.get<T>(path, queryParameters: queryParameters);

  Future<Response<T>> post<T>(String path, {dynamic data}) =>
    _dio.post<T>(path, data: data);

  Future<Response<T>> put<T>(String path, {dynamic data}) =>
    _dio.put<T>(path, data: data);

  Future<Response<T>> delete<T>(String path) =>
    _dio.delete<T>(path);
}

class _AuthInterceptor extends Interceptor {
  final SecureStorage _storage;
  final Dio _dio;

  _AuthInterceptor(this._storage, this._dio);

  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) async {
    final token = await _storage.getAccessToken();
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    handler.next(options);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) async {
    if (err.response?.statusCode == 401) {
      // Try to refresh token
      final refreshToken = await _storage.getRefreshToken();
      if (refreshToken != null) {
        try {
          final response = await _dio.post('/auth/refresh', data: {'refresh_token': refreshToken});
          final newToken = response.data['access_token'];
          await _storage.setAccessToken(newToken);

          // Retry original request
          err.requestOptions.headers['Authorization'] = 'Bearer $newToken';
          final retryResponse = await _dio.fetch(err.requestOptions);
          handler.resolve(retryResponse);
          return;
        } catch (_) {
          // Refresh failed, logout
          await _storage.clearTokens();
        }
      }
    }
    handler.next(err);
  }
}

class _LoggingInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    print('API Request: ${options.method} ${options.path}');
    handler.next(options);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    print('API Response: ${response.statusCode} ${response.requestOptions.path}');
    handler.next(response);
  }
}
```

#### Day 311 Deliverables
- [x] API gateway with standardized responses
- [x] Rate limiting with Redis
- [x] OpenAPI documentation
- [x] API testing framework
- [x] Flutter project architecture
- [x] API client with interceptors

---

### Day 312: Authentication System

**Theme:** JWT Authentication & User Management

#### Dev 1 - Backend Lead (8h)

**Task 1: JWT Token Service (5h)**
```python
# farm_mobile_api/models/api_token.py
import jwt
import secrets
from datetime import datetime, timedelta
from odoo import models, fields, api
from odoo.exceptions import AccessDenied

class FarmAPIToken(models.Model):
    _name = 'farm.api.token'
    _description = 'API Token Management'

    user_id = fields.Many2one('res.users', required=True, ondelete='cascade')
    access_token = fields.Char(string='Access Token', required=True, index=True)
    refresh_token = fields.Char(string='Refresh Token', required=True, index=True)
    device_id = fields.Char(string='Device ID')
    device_name = fields.Char(string='Device Name')
    expires_at = fields.Datetime(string='Expires At')
    refresh_expires_at = fields.Datetime(string='Refresh Expires At')
    is_revoked = fields.Boolean(default=False)
    last_used = fields.Datetime()

    _sql_constraints = [
        ('access_token_unique', 'UNIQUE(access_token)', 'Access token must be unique'),
        ('refresh_token_unique', 'UNIQUE(refresh_token)', 'Refresh token must be unique'),
    ]

    @api.model
    def _get_secret_key(self):
        return self.env['ir.config_parameter'].sudo().get_param(
            'farm.jwt.secret',
            default='your-super-secret-key-change-in-production'
        )

    @api.model
    def generate_tokens(self, user_id, device_id=None, device_name=None):
        """Generate access and refresh tokens for user"""
        access_expires = datetime.utcnow() + timedelta(hours=24)
        refresh_expires = datetime.utcnow() + timedelta(days=30)

        access_payload = {
            'user_id': user_id,
            'type': 'access',
            'exp': access_expires,
            'iat': datetime.utcnow(),
            'jti': secrets.token_urlsafe(16),
        }

        refresh_payload = {
            'user_id': user_id,
            'type': 'refresh',
            'exp': refresh_expires,
            'iat': datetime.utcnow(),
            'jti': secrets.token_urlsafe(16),
        }

        secret = self._get_secret_key()
        access_token = jwt.encode(access_payload, secret, algorithm='HS256')
        refresh_token = jwt.encode(refresh_payload, secret, algorithm='HS256')

        # Store in database
        self.create({
            'user_id': user_id,
            'access_token': access_token,
            'refresh_token': refresh_token,
            'device_id': device_id,
            'device_name': device_name,
            'expires_at': access_expires,
            'refresh_expires_at': refresh_expires,
        })

        return {
            'access_token': access_token,
            'refresh_token': refresh_token,
            'expires_in': 86400,  # 24 hours in seconds
            'token_type': 'Bearer',
        }

    @api.model
    def verify_token(self, token):
        """Verify JWT token and return payload"""
        try:
            secret = self._get_secret_key()
            payload = jwt.decode(token, secret, algorithms=['HS256'])

            # Check if token is revoked
            token_record = self.search([
                ('access_token', '=', token),
                ('is_revoked', '=', False),
            ], limit=1)

            if not token_record:
                raise AccessDenied('Token has been revoked')

            # Update last used
            token_record.last_used = fields.Datetime.now()

            return payload

        except jwt.ExpiredSignatureError:
            raise AccessDenied('Token has expired')
        except jwt.InvalidTokenError:
            raise AccessDenied('Invalid token')

    @api.model
    def refresh_access_token(self, refresh_token):
        """Generate new access token using refresh token"""
        try:
            secret = self._get_secret_key()
            payload = jwt.decode(refresh_token, secret, algorithms=['HS256'])

            if payload.get('type') != 'refresh':
                raise AccessDenied('Invalid refresh token')

            token_record = self.search([
                ('refresh_token', '=', refresh_token),
                ('is_revoked', '=', False),
            ], limit=1)

            if not token_record:
                raise AccessDenied('Refresh token has been revoked')

            # Generate new access token
            user_id = payload['user_id']
            access_expires = datetime.utcnow() + timedelta(hours=24)

            access_payload = {
                'user_id': user_id,
                'type': 'access',
                'exp': access_expires,
                'iat': datetime.utcnow(),
                'jti': secrets.token_urlsafe(16),
            }

            new_access_token = jwt.encode(access_payload, secret, algorithm='HS256')

            # Update record
            token_record.write({
                'access_token': new_access_token,
                'expires_at': access_expires,
            })

            return {
                'access_token': new_access_token,
                'expires_in': 86400,
            }

        except jwt.ExpiredSignatureError:
            raise AccessDenied('Refresh token has expired')
        except jwt.InvalidTokenError:
            raise AccessDenied('Invalid refresh token')

    def revoke(self):
        """Revoke token"""
        self.is_revoked = True
```

**Task 2: Authentication Controller (3h)**
```python
# farm_mobile_api/controllers/auth_controller.py
from odoo import http
from odoo.http import request
from odoo.exceptions import AccessDenied
import json

class AuthController(http.Controller):

    @http.route('/api/v1/auth/login', type='json', auth='none', methods=['POST'], csrf=False)
    def login(self, username, password, device_id=None, device_name=None):
        """Authenticate user and return tokens"""
        try:
            uid = request.session.authenticate(request.db, username, password)
            if not uid:
                return {'success': False, 'error': 'Invalid credentials'}

            # Generate tokens
            TokenModel = request.env['farm.api.token'].sudo()
            tokens = TokenModel.generate_tokens(uid, device_id, device_name)

            # Get user info
            user = request.env['res.users'].sudo().browse(uid)

            return {
                'success': True,
                'data': {
                    **tokens,
                    'user': {
                        'id': user.id,
                        'name': user.name,
                        'email': user.email,
                        'farm_ids': user.farm_ids.ids if hasattr(user, 'farm_ids') else [],
                        'role': self._get_user_role(user),
                    }
                }
            }
        except AccessDenied:
            return {'success': False, 'error': 'Invalid credentials'}

    @http.route('/api/v1/auth/refresh', type='json', auth='none', methods=['POST'], csrf=False)
    def refresh_token(self, refresh_token):
        """Refresh access token"""
        try:
            TokenModel = request.env['farm.api.token'].sudo()
            result = TokenModel.refresh_access_token(refresh_token)
            return {'success': True, 'data': result}
        except AccessDenied as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/auth/logout', type='json', auth='user', methods=['POST'])
    def logout(self):
        """Logout and revoke token"""
        auth_header = request.httprequest.headers.get('Authorization', '')
        if auth_header.startswith('Bearer '):
            token = auth_header.split(' ')[1]
            token_record = request.env['farm.api.token'].sudo().search([
                ('access_token', '=', token)
            ], limit=1)
            if token_record:
                token_record.revoke()

        return {'success': True, 'message': 'Logged out successfully'}

    @http.route('/api/v1/auth/me', type='json', auth='user', methods=['GET'])
    def get_current_user(self):
        """Get current user info"""
        user = request.env.user
        return {
            'success': True,
            'data': {
                'id': user.id,
                'name': user.name,
                'email': user.email,
                'role': self._get_user_role(user),
            }
        }

    def _get_user_role(self, user):
        if user.has_group('farm_base.group_farm_admin'):
            return 'admin'
        elif user.has_group('farm_base.group_farm_manager'):
            return 'manager'
        return 'user'
```

#### Dev 2 - Full-Stack (8h)

**Task 1: User Session Management (4h)**
```python
# farm_mobile_api/models/user_session.py
class FarmUserSession(models.Model):
    _name = 'farm.user.session'
    _description = 'Mobile User Session'

    user_id = fields.Many2one('res.users', required=True, ondelete='cascade')
    device_id = fields.Char(required=True, index=True)
    device_model = fields.Char()
    os_version = fields.Char()
    app_version = fields.Char()
    push_token = fields.Char(string='Push Notification Token')
    last_active = fields.Datetime(default=fields.Datetime.now)
    last_sync = fields.Datetime()
    is_active = fields.Boolean(default=True)

    # Location tracking (optional)
    last_latitude = fields.Float()
    last_longitude = fields.Float()

    @api.model
    def update_session(self, device_id, **kwargs):
        session = self.search([
            ('user_id', '=', self.env.uid),
            ('device_id', '=', device_id),
        ], limit=1)

        vals = {
            'last_active': fields.Datetime.now(),
            **kwargs,
        }

        if session:
            session.write(vals)
        else:
            vals.update({
                'user_id': self.env.uid,
                'device_id': device_id,
            })
            session = self.create(vals)

        return session
```

**Task 2: Permission & Access Control (4h)**
```python
# farm_mobile_api/controllers/permission_api.py
class PermissionAPI(http.Controller):

    @http.route('/api/v1/permissions', type='json', auth='user', methods=['GET'])
    def get_permissions(self):
        """Get user permissions for mobile app"""
        user = request.env.user

        permissions = {
            # Animal module
            'animal.view': user.has_group('farm_base.group_farm_user'),
            'animal.create': user.has_group('farm_base.group_farm_manager'),
            'animal.edit': user.has_group('farm_base.group_farm_manager'),
            'animal.delete': user.has_group('farm_base.group_farm_admin'),

            # Health module
            'health.view': user.has_group('farm_base.group_farm_user'),
            'health.create': user.has_group('farm_base.group_farm_user'),
            'health.approve': user.has_group('farm_base.group_farm_manager'),

            # Production module
            'production.view': user.has_group('farm_base.group_farm_user'),
            'production.create': user.has_group('farm_base.group_farm_user'),
            'production.edit': user.has_group('farm_base.group_farm_manager'),

            # Feed module
            'feed.view': user.has_group('farm_base.group_farm_user'),
            'feed.manage': user.has_group('farm_base.group_farm_manager'),

            # Reports
            'reports.view': user.has_group('farm_base.group_farm_manager'),
            'reports.export': user.has_group('farm_base.group_farm_manager'),

            # Admin functions
            'settings.manage': user.has_group('farm_base.group_farm_admin'),
            'users.manage': user.has_group('farm_base.group_farm_admin'),
        }

        return {'success': True, 'data': permissions}
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Flutter Auth Bloc (5h)**
```dart
// lib/features/auth/presentation/bloc/auth_bloc.dart
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:injectable/injectable.dart';
import '../../domain/repositories/auth_repository.dart';

// Events
abstract class AuthEvent {}

class AuthCheckRequested extends AuthEvent {}

class AuthLoginRequested extends AuthEvent {
  final String username;
  final String password;
  AuthLoginRequested(this.username, this.password);
}

class AuthLogoutRequested extends AuthEvent {}

class AuthTokenRefreshRequested extends AuthEvent {}

// States
abstract class AuthState {}

class AuthInitial extends AuthState {}

class AuthLoading extends AuthState {}

class AuthAuthenticated extends AuthState {
  final User user;
  AuthAuthenticated(this.user);
}

class AuthUnauthenticated extends AuthState {}

class AuthError extends AuthState {
  final String message;
  AuthError(this.message);
}

// Bloc
@injectable
class AuthBloc extends Bloc<AuthEvent, AuthState> {
  final AuthRepository _authRepository;
  final SecureStorage _storage;

  AuthBloc(this._authRepository, this._storage) : super(AuthInitial()) {
    on<AuthCheckRequested>(_onCheckRequested);
    on<AuthLoginRequested>(_onLoginRequested);
    on<AuthLogoutRequested>(_onLogoutRequested);
    on<AuthTokenRefreshRequested>(_onTokenRefreshRequested);
  }

  Future<void> _onCheckRequested(AuthCheckRequested event, Emitter<AuthState> emit) async {
    final token = await _storage.getAccessToken();
    if (token != null) {
      try {
        final user = await _authRepository.getCurrentUser();
        emit(AuthAuthenticated(user));
      } catch (_) {
        // Token might be expired, try refresh
        add(AuthTokenRefreshRequested());
      }
    } else {
      emit(AuthUnauthenticated());
    }
  }

  Future<void> _onLoginRequested(AuthLoginRequested event, Emitter<AuthState> emit) async {
    emit(AuthLoading());
    try {
      final result = await _authRepository.login(event.username, event.password);
      await _storage.setAccessToken(result.accessToken);
      await _storage.setRefreshToken(result.refreshToken);
      emit(AuthAuthenticated(result.user));
    } catch (e) {
      emit(AuthError(e.toString()));
    }
  }

  Future<void> _onLogoutRequested(AuthLogoutRequested event, Emitter<AuthState> emit) async {
    await _authRepository.logout();
    await _storage.clearTokens();
    emit(AuthUnauthenticated());
  }

  Future<void> _onTokenRefreshRequested(AuthTokenRefreshRequested event, Emitter<AuthState> emit) async {
    final refreshToken = await _storage.getRefreshToken();
    if (refreshToken != null) {
      try {
        final newToken = await _authRepository.refreshToken(refreshToken);
        await _storage.setAccessToken(newToken);
        final user = await _authRepository.getCurrentUser();
        emit(AuthAuthenticated(user));
      } catch (_) {
        await _storage.clearTokens();
        emit(AuthUnauthenticated());
      }
    } else {
      emit(AuthUnauthenticated());
    }
  }
}
```

**Task 2: Login Screen UI (3h)**
```dart
// lib/features/auth/presentation/screens/login_screen.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../bloc/auth_bloc.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _usernameController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocConsumer<AuthBloc, AuthState>(
        listener: (context, state) {
          if (state is AuthAuthenticated) {
            context.go('/dashboard');
          } else if (state is AuthError) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text(state.message), backgroundColor: Colors.red),
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
                    const SizedBox(height: 60),
                    // Logo
                    Image.asset('assets/images/logo.png', height: 100),
                    const SizedBox(height: 24),
                    const Text(
                      'Smart Dairy',
                      style: TextStyle(fontSize: 28, fontWeight: FontWeight.bold),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 48),
                    // Username field
                    TextFormField(
                      controller: _usernameController,
                      decoration: const InputDecoration(
                        labelText: 'Username',
                        prefixIcon: Icon(Icons.person),
                        border: OutlineInputBorder(),
                      ),
                      validator: (v) => v?.isEmpty == true ? 'Required' : null,
                    ),
                    const SizedBox(height: 16),
                    // Password field
                    TextFormField(
                      controller: _passwordController,
                      obscureText: _obscurePassword,
                      decoration: InputDecoration(
                        labelText: 'Password',
                        prefixIcon: const Icon(Icons.lock),
                        border: const OutlineInputBorder(),
                        suffixIcon: IconButton(
                          icon: Icon(_obscurePassword ? Icons.visibility : Icons.visibility_off),
                          onPressed: () => setState(() => _obscurePassword = !_obscurePassword),
                        ),
                      ),
                      validator: (v) => v?.isEmpty == true ? 'Required' : null,
                    ),
                    const SizedBox(height: 24),
                    // Login button
                    ElevatedButton(
                      onPressed: state is AuthLoading ? null : _login,
                      style: ElevatedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 16),
                      ),
                      child: state is AuthLoading
                          ? const CircularProgressIndicator(color: Colors.white)
                          : const Text('Login', style: TextStyle(fontSize: 16)),
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

  void _login() {
    if (_formKey.currentState!.validate()) {
      context.read<AuthBloc>().add(
        AuthLoginRequested(_usernameController.text, _passwordController.text),
      );
    }
  }
}
```

#### Day 312 Deliverables
- [x] JWT token service with refresh
- [x] Authentication controller
- [x] User session management
- [x] Permission API
- [x] Flutter Auth Bloc
- [x] Login screen UI

---

### Days 313-320: Summary

#### Day 313: Offline Storage Architecture
- Drift database schema for local storage
- Hive for key-value caching
- Data models with sync status
- Migration system

#### Day 314: Animal Module (Mobile)
- Animal list with search/filter
- Animal detail screen
- Animal registration form
- Quick animal lookup

#### Day 315: Health Module (Mobile)
- Health record list
- Health record entry form
- Vaccination tracking
- Treatment records

#### Day 316: Production Module (Mobile)
- Production entry quick form
- Batch entry grid
- Daily production summary
- Quality parameter entry

#### Day 317: RFID/Barcode Scanning
- Camera-based barcode scanning
- NFC RFID reading
- QR code scanning
- Scan history

#### Day 318: Bangla Voice Input
- Speech-to-text integration
- Voice command processing
- Bangla number recognition
- Voice feedback

#### Day 319: Photo & Location
- Camera integration with preview
- GPS geotagging
- Photo compression
- Gallery integration

#### Day 320: Sync & Testing
- Background sync service
- Conflict resolution
- E2E mobile testing
- Performance optimization

---

## 4. Technical Specifications

### 4.1 Drift Database Schema

```dart
// lib/core/database/database.dart
import 'package:drift/drift.dart';

part 'database.g.dart';

class Animals extends Table {
  IntColumn get id => integer().autoIncrement()();
  IntColumn get serverId => integer().nullable()();
  TextColumn get name => text()();
  TextColumn get earTag => text()();
  TextColumn get rfidTag => text().nullable()();
  TextColumn get status => text()();
  TextColumn get syncStatus => text().withDefault(const Constant('pending'))();
  DateTimeColumn get lastModified => dateTime()();
  DateTimeColumn get syncedAt => dateTime().nullable()();
}

class HealthRecords extends Table {
  IntColumn get id => integer().autoIncrement()();
  IntColumn get serverId => integer().nullable()();
  IntColumn get animalId => integer().references(Animals, #id)();
  DateTimeColumn get recordDate => dateTime()();
  TextColumn get recordType => text()();
  TextColumn get diagnosis => text().nullable()();
  TextColumn get treatment => text().nullable()();
  TextColumn get syncStatus => text().withDefault(const Constant('pending'))();
}

class MilkProductions extends Table {
  IntColumn get id => integer().autoIncrement()();
  IntColumn get serverId => integer().nullable()();
  IntColumn get animalId => integer().references(Animals, #id)();
  DateTimeColumn get productionDate => dateTime()();
  TextColumn get session => text()(); // morning, evening
  RealColumn get yieldLiters => real()();
  RealColumn get fatPercent => real().nullable()();
  RealColumn get proteinPercent => real().nullable()();
  TextColumn get syncStatus => text().withDefault(const Constant('pending'))();
}

@DriftDatabase(tables: [Animals, HealthRecords, MilkProductions])
class AppDatabase extends _$AppDatabase {
  AppDatabase() : super(_openConnection());

  @override
  int get schemaVersion => 1;
}
```

### 4.2 API Endpoints Summary

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/auth/login` | POST | User login |
| `/api/v1/auth/refresh` | POST | Refresh token |
| `/api/v1/auth/logout` | POST | Logout |
| `/api/v1/animals` | GET/POST | Animal CRUD |
| `/api/v1/animals/<id>` | GET/PUT/DELETE | Single animal |
| `/api/v1/health-records` | GET/POST | Health records |
| `/api/v1/productions` | GET/POST | Production records |
| `/api/v1/sync/upload` | POST | Upload local changes |
| `/api/v1/sync/download` | GET | Download changes |

---

## 5. Testing & Validation

### 5.1 Mobile Test Strategy
- Unit tests: BLoC logic, repositories
- Widget tests: UI components
- Integration tests: API communication
- E2E tests: Full user flows

### 5.2 Acceptance Criteria
| Criteria | Target |
|----------|--------|
| Offline CRUD | All operations work |
| Sync reliability | >99% success |
| Scan accuracy | >95% |
| Voice accuracy (Bangla) | >85% |

---

## 6. Dependencies & Handoffs

### 6.1 Prerequisites
- Milestones 41-46: Backend data models and APIs

### 6.2 Outputs
- Milestone 49: Mobile report viewing
- Milestone 50: Mobile E2E testing

---

*Document End - Milestone 47: Mobile App Integration*
