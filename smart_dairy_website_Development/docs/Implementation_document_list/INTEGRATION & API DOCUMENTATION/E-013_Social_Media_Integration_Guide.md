# Document E-013: Social Media Integration Guide

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | E-013 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Status** | Draft |
| **Classification** | Internal Use |

### Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 2026-01-31 | Integration Engineer | Initial version - Social Media Integration Guide |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Platform Overview](#2-platform-overview)
3. [Social Login (OAuth)](#3-social-login-oauth)
4. [Social Sharing](#4-social-sharing)
5. [Facebook Integration](#5-facebook-integration)
6. [Instagram Integration](#6-instagram-integration)
7. [Google My Business](#7-google-my-business)
8. [Social Media Analytics](#8-social-media-analytics)
9. [Content Publishing](#9-content-publishing)
10. [Review Management](#10-review-management)
11. [Implementation](#11-implementation)
12. [Privacy & Compliance](#12-privacy--compliance)
13. [Appendices](#13-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive guidance for integrating social media platforms with the Smart Dairy Ltd. web portal. The integration enables enhanced user engagement, simplified authentication, automated content distribution, and consolidated social media management.

### 1.2 Scope

This guide covers:
- Social authentication (OAuth 2.0)
- Social sharing capabilities
- Platform-specific integrations (Facebook, Instagram, LinkedIn, Google)
- Automated content publishing from product catalog
- Review aggregation and management
- Social media analytics and reporting

### 1.3 Use Cases

| Use Case | Description | Priority |
|----------|-------------|----------|
| Social Authentication | Allow users to register/login using Google, Facebook, or LinkedIn accounts | High |
| Product Sharing | Enable users to share product pages to social media platforms | High |
| Automated Announcements | Auto-post new products, promotions, and updates to social channels | Medium |
| Review Aggregation | Collect and analyze reviews from multiple social platforms | Medium |
| Social Proof Display | Show social media activity and reviews on product pages | Medium |
| Social Analytics | Track engagement metrics across all connected platforms | Low |

### 1.4 Integration Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                     Smart Dairy Web Portal                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐   │
│  │   Flutter    │  │   FastAPI    │  │   PostgreSQL/MySQL   │   │
│  │   Frontend   │◄─┤   Backend    │◄─┤      Database        │   │
│  └──────┬───────┘  └──────┬───────┘  └──────────────────────┘   │
│         │                 │                                       │
│         │ OAuth/Social    │ API Calls                             │
│         │ Sharing         │                                       │
│         ▼                 ▼                                       │
│  ┌─────────────────────────────────────────────────────────┐     │
│  │              Social Media Integration Layer              │     │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌───────────────┐  │     │
│  │  │ Google  │ │ Facebook│ │LinkedIn │ │     X         │  │     │
│  │  │  OAuth  │ │ Graph   │ │  API    │ │   (Twitter)   │  │     │
│  │  └─────────┘ └─────────┘ └─────────┘ └───────────────┘  │     │
│  └─────────────────────────────────────────────────────────┘     │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. Platform Overview

### 2.1 Supported Platforms

| Platform | API Name | Primary Use | Authentication |
|----------|----------|-------------|----------------|
| Google | Google Identity Services | Login, Analytics | OAuth 2.0 |
| Facebook | Graph API | Login, Sharing, Pages | OAuth 2.0 |
| Instagram | Instagram Graph API | Business accounts | OAuth 2.0 |
| LinkedIn | LinkedIn API | Login, Sharing | OAuth 2.0 |
| X (Twitter) | X API v2 | Sharing, Analytics | OAuth 2.0 |
| Google My Business | GMB API | Location, Reviews | OAuth 2.0 |

### 2.2 API Comparison

| Feature | Google | Facebook | LinkedIn | X | Instagram |
|---------|--------|----------|----------|---|-----------|
| Social Login | ✓ | ✓ | ✓ | ✓ | ✗ |
| Share Product | ✓ | ✓ | ✓ | ✓ | ✗ |
| Post to Page | ✗ | ✓ | ✓ | ✓ | ✓ (via FB) |
| Analytics | ✓ | ✓ | ✓ | ✓ | ✓ |
| Reviews API | ✓ | ✓ | ✗ | ✗ | ✗ |
| Webhooks | ✓ | ✓ | ✓ | ✓ | ✓ |

### 2.3 API Rate Limits

| Platform | Standard Limit | Notes |
|----------|----------------|-------|
| Google | 10,000 req/day | Identity Services: 100 req/100 sec/user |
| Facebook | 200 calls/hour/user | Increases with app usage |
| LinkedIn | 500 req/day | Basic access; higher tiers available |
| X | 300 req/15 min | Read limit; write limits vary |
| Instagram | 200 calls/hour/user | Business accounts only |

---

## 3. Social Login (OAuth)

### 3.1 OAuth 2.0 Flow Overview

```
┌─────────┐                                    ┌─────────┐
│  User   │                                    │  Smart  │
│ (Browser)│                                   │  Dairy  │
└────┬────┘                                    └────┬────┘
     │                                              │
     │ 1. Click "Login with Google/Facebook"       │
     │─────────────────────────────────────────────>│
     │                                              │
     │ 2. Redirect to Provider Authorization       │
     │<─────────────────────────────────────────────│
     │                                              │
     │ 3. User Authorizes App                      │
     │─────────────────────────────────────────────>│
     │                    (Provider)                │
     │ 4. Authorization Code + Redirect            │
     │<─────────────────────────────────────────────│
     │                                              │
     │ 5. Send Code to Backend                     │
     │─────────────────────────────────────────────>│
     │                                              │
     │ 6. Exchange Code for Tokens                 │
     │              (Backend ↔ Provider)            │
     │                                              │
     │ 7. Return JWT/Session                       │
     │<─────────────────────────────────────────────│
     │                                              │
```

### 3.2 Google Sign-In Integration

#### 3.2.1 Configuration

**Google Cloud Console Setup:**
1. Create project at [console.cloud.google.com](https://console.cloud.google.com)
2. Enable Google Identity Services API
3. Configure OAuth consent screen
4. Create OAuth 2.0 credentials
5. Add authorized redirect URIs:
   - `https://smartdairy.com/api/v1/auth/google/callback`
   - `http://localhost:8000/api/v1/auth/google/callback` (dev)

#### 3.2.2 Backend Implementation (FastAPI)

```python
# app/services/auth/google_auth.py
from fastapi import HTTPException, status
from google.oauth2 import id_token
from google.auth.transport import requests as google_requests
import httpx
from pydantic import BaseModel

class GoogleUserInfo(BaseModel):
    email: str
    name: str
    picture: str | None
    sub: str  # Google's unique user ID

class GoogleAuthService:
    def __init__(self, client_id: str, client_secret: str, redirect_uri: str):
        self.client_id = client_id
        self.client_secret = client_secret
        self.redirect_uri = redirect_uri
        self.token_url = "https://oauth2.googleapis.com/token"
        self.userinfo_url = "https://www.googleapis.com/oauth2/v3/userinfo"
    
    def get_authorization_url(self, state: str) -> str:
        """Generate Google OAuth authorization URL."""
        params = {
            "client_id": self.client_id,
            "redirect_uri": self.redirect_uri,
            "response_type": "code",
            "scope": "openid email profile",
            "state": state,
            "access_type": "offline",
            "prompt": "consent"
        }
        query = "&".join(f"{k}={v}" for k, v in params.items())
        return f"https://accounts.google.com/o/oauth2/v2/auth?{query}"
    
    async def exchange_code_for_tokens(self, code: str) -> dict:
        """Exchange authorization code for access tokens."""
        async with httpx.AsyncClient() as client:
            response = await client.post(
                self.token_url,
                data={
                    "code": code,
                    "client_id": self.client_id,
                    "client_secret": self.client_secret,
                    "redirect_uri": self.redirect_uri,
                    "grant_type": "authorization_code"
                }
            )
            
            if response.status_code != 200:
                raise HTTPException(
                    status_code=status.HTTP_400_BAD_REQUEST,
                    detail=f"Failed to exchange code: {response.text}"
                )
            
            return response.json()
    
    async def get_user_info(self, access_token: str) -> GoogleUserInfo:
        """Fetch user information using access token."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                self.userinfo_url,
                headers={"Authorization": f"Bearer {access_token}"}
            )
            
            if response.status_code != 200:
                raise HTTPException(
                    status_code=status.HTTP_400_BAD_REQUEST,
                    detail="Failed to fetch user info"
                )
            
            data = response.json()
            return GoogleUserInfo(**data)
    
    def verify_id_token(self, id_token_str: str) -> dict:
        """Verify Google ID token (for mobile apps)."""
        try:
            return id_token.verify_oauth2_token(
                id_token_str,
                google_requests.Request(),
                self.client_id
            )
        except ValueError as e:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail=f"Invalid ID token: {str(e)}"
            )

# app/api/v1/auth.py
from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.responses import RedirectResponse
from sqlalchemy.orm import Session
from app.core.database import get_db
from app.services.auth.google_auth import GoogleAuthService
from app.services.auth.jwt_service import create_access_token
from app.models.user import User
from app.schemas.auth import SocialLoginRequest, TokenResponse
import secrets

router = APIRouter(prefix="/auth", tags=["Authentication"])

# Initialize service with config
google_auth = GoogleAuthService(
    client_id=settings.GOOGLE_CLIENT_ID,
    client_secret=settings.GOOGLE_CLIENT_SECRET,
    redirect_uri=settings.GOOGLE_REDIRECT_URI
)

@router.get("/google/login")
async def google_login():
    """Initiate Google OAuth flow."""
    state = secrets.token_urlsafe(32)
    # Store state in session/cache for validation
    auth_url = google_auth.get_authorization_url(state)
    return RedirectResponse(url=auth_url)

@router.get("/google/callback")
async def google_callback(
    code: str,
    state: str,
    db: Session = Depends(get_db)
):
    """Handle Google OAuth callback."""
    # Verify state parameter
    # ... state validation logic ...
    
    # Exchange code for tokens
    tokens = await google_auth.exchange_code_for_tokens(code)
    
    # Get user info
    user_info = await google_auth.get_user_info(tokens["access_token"])
    
    # Find or create user
    user = db.query(User).filter(User.email == user_info.email).first()
    
    if not user:
        user = User(
            email=user_info.email,
            full_name=user_info.name,
            profile_picture=user_info.picture,
            google_id=user_info.sub,
            auth_provider="google"
        )
        db.add(user)
        db.commit()
        db.refresh(user)
    
    # Generate JWT token
    access_token = create_access_token(
        data={"sub": user.email, "user_id": user.id}
    )
    
    return TokenResponse(access_token=access_token, token_type="bearer")

@router.post("/google/mobile")
async def google_mobile_login(
    request: SocialLoginRequest,
    db: Session = Depends(get_db)
):
    """Handle Google Sign-In from Flutter mobile app."""
    # Verify ID token from mobile
    id_info = google_auth.verify_id_token(request.id_token)
    
    email = id_info.get("email")
    name = id_info.get("name", "")
    picture = id_info.get("picture")
    google_id = id_info.get("sub")
    
    # Find or create user (same as above)
    # ... user logic ...
    
    access_token = create_access_token(data={"sub": email, "user_id": user.id})
    return TokenResponse(access_token=access_token, token_type="bearer")
```

### 3.3 Facebook Login Integration

#### 3.3.1 Configuration

**Facebook Developers Setup:**
1. Create app at [developers.facebook.com](https://developers.facebook.com)
2. Add Facebook Login product
3. Configure OAuth redirect URIs
4. Request required permissions:
   - `email` - Access user's email
   - `public_profile` - Basic profile info
   - `pages_read_engagement` - For page management

#### 3.3.2 Backend Implementation

```python
# app/services/auth/facebook_auth.py
import httpx
from pydantic import BaseModel
from fastapi import HTTPException, status

class FacebookUserInfo(BaseModel):
    id: str
    email: str
    name: str
    picture: dict | None

class FacebookAuthService:
    def __init__(self, app_id: str, app_secret: str, redirect_uri: str):
        self.app_id = app_id
        self.app_secret = app_secret
        self.redirect_uri = redirect_uri
        self.base_url = "https://graph.facebook.com/v18.0"
    
    def get_authorization_url(self, state: str) -> str:
        """Generate Facebook OAuth authorization URL."""
        params = {
            "client_id": self.app_id,
            "redirect_uri": self.redirect_uri,
            "response_type": "code",
            "scope": "email,public_profile",
            "state": state
        }
        query = "&".join(f"{k}={v}" for k, v in params.items())
        return f"https://www.facebook.com/v18.0/dialog/oauth?{query}"
    
    async def exchange_code_for_tokens(self, code: str) -> dict:
        """Exchange authorization code for access token."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/oauth/access_token",
                params={
                    "client_id": self.app_id,
                    "client_secret": self.app_secret,
                    "code": code,
                    "redirect_uri": self.redirect_uri
                }
            )
            
            if response.status_code != 200:
                raise HTTPException(
                    status_code=status.HTTP_400_BAD_REQUEST,
                    detail="Failed to exchange Facebook code"
                )
            
            return response.json()
    
    async def get_user_info(self, access_token: str) -> FacebookUserInfo:
        """Fetch user information from Facebook."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/me",
                params={
                    "access_token": access_token,
                    "fields": "id,name,email,picture"
                }
            )
            
            if response.status_code != 200:
                raise HTTPException(
                    status_code=status.HTTP_400_BAD_REQUEST,
                    detail="Failed to fetch Facebook user info"
                )
            
            return FacebookUserInfo(**response.json())
    
    async def debug_token(self, input_token: str) -> dict:
        """Verify access token validity."""
        app_access_token = f"{self.app_id}|{self.app_secret}"
        
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/debug_token",
                params={
                    "input_token": input_token,
                    "access_token": app_access_token
                }
            )
            return response.json()
```

### 3.4 LinkedIn OAuth Integration

```python
# app/services/auth/linkedin_auth.py
import httpx
from pydantic import BaseModel
from fastapi import HTTPException, status

class LinkedInUserInfo(BaseModel):
    sub: str  # Unique LinkedIn ID
    email: str
    given_name: str
    family_name: str
    picture: str | None

class LinkedInAuthService:
    def __init__(self, client_id: str, client_secret: str, redirect_uri: str):
        self.client_id = client_id
        self.client_secret = client_secret
        self.redirect_uri = redirect_uri
    
    def get_authorization_url(self, state: str) -> str:
        """Generate LinkedIn OAuth authorization URL."""
        params = {
            "response_type": "code",
            "client_id": self.client_id,
            "redirect_uri": self.redirect_uri,
            "state": state,
            "scope": "openid profile email"
        }
        query = "&".join(f"{k}={v}" for k, v in params.items())
        return f"https://www.linkedin.com/oauth/v2/authorization?{query}"
    
    async def exchange_code_for_tokens(self, code: str) -> dict:
        """Exchange authorization code for tokens."""
        async with httpx.AsyncClient() as client:
            response = await client.post(
                "https://www.linkedin.com/oauth/v2/accessToken",
                data={
                    "grant_type": "authorization_code",
                    "code": code,
                    "client_id": self.client_id,
                    "client_secret": self.client_secret,
                    "redirect_uri": self.redirect_uri
                },
                headers={"Content-Type": "application/x-www-form-urlencoded"}
            )
            
            if response.status_code != 200:
                raise HTTPException(
                    status_code=status.HTTP_400_BAD_REQUEST,
                    detail="Failed to exchange LinkedIn code"
                )
            
            return response.json()
    
    async def get_user_info(self, access_token: str) -> LinkedInUserInfo:
        """Fetch user information from LinkedIn OpenID Connect."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                "https://api.linkedin.com/v2/userinfo",
                headers={"Authorization": f"Bearer {access_token}"}
            )
            
            if response.status_code != 200:
                raise HTTPException(
                    status_code=status.HTTP_400_BAD_REQUEST,
                    detail="Failed to fetch LinkedIn user info"
                )
            
            return LinkedInUserInfo(**response.json())
```

### 3.5 Flutter Frontend Integration

```dart
// lib/services/social_auth_service.dart
import 'package:google_sign_in/google_sign_in.dart';
import 'package:flutter_facebook_auth/flutter_facebook_auth.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class SocialAuthService {
  final String apiBaseUrl;
  
  SocialAuthService({required this.apiBaseUrl});
  
  // Google Sign-In
  Future<AuthResult> signInWithGoogle() async {
    try {
      final GoogleSignIn googleSignIn = GoogleSignIn(
        scopes: ['email', 'profile'],
        clientId: 'YOUR_WEB_CLIENT_ID', // For web
        serverClientId: 'YOUR_SERVER_CLIENT_ID', // For backend verification
      );
      
      final GoogleSignInAccount? googleUser = await googleSignIn.signIn();
      
      if (googleUser == null) {
        return AuthResult.cancelled();
      }
      
      final GoogleSignInAuthentication googleAuth = 
          await googleUser.authentication;
      
      // Send ID token to backend
      final response = await http.post(
        Uri.parse('$apiBaseUrl/auth/google/mobile'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'id_token': googleAuth.idToken,
          'access_token': googleAuth.accessToken,
        }),
      );
      
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return AuthResult.success(
          token: data['access_token'],
          user: User.fromJson(data['user']),
        );
      } else {
        return AuthResult.error('Backend authentication failed');
      }
    } catch (e) {
      return AuthResult.error(e.toString());
    }
  }
  
  // Facebook Login
  Future<AuthResult> signInWithFacebook() async {
    try {
      final LoginResult result = await FacebookAuth.instance.login(
        permissions: ['email', 'public_profile'],
      );
      
      if (result.status == LoginStatus.cancelled) {
        return AuthResult.cancelled();
      }
      
      if (result.status == LoginStatus.failed) {
        return AuthResult.error(result.message ?? 'Facebook login failed');
      }
      
      final AccessToken accessToken = result.accessToken!;
      
      // Get user data
      final userData = await FacebookAuth.instance.getUserData(
        fields: "email,name,picture",
      );
      
      // Send to backend
      final response = await http.post(
        Uri.parse('$apiBaseUrl/auth/facebook/mobile'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'access_token': accessToken.token,
          'user_data': userData,
        }),
      );
      
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return AuthResult.success(
          token: data['access_token'],
          user: User.fromJson(data['user']),
        );
      } else {
        return AuthResult.error('Backend authentication failed');
      }
    } catch (e) {
      return AuthResult.error(e.toString());
    }
  }
  
  // Sign out from all providers
  Future<void> signOut() async {
    await GoogleSignIn().signOut();
    await FacebookAuth.instance.logOut();
  }
}

// Auth result model
class AuthResult {
  final bool success;
  final String? token;
  final User? user;
  final String? error;
  final bool cancelled;
  
  AuthResult._({
    required this.success,
    this.token,
    this.user,
    this.error,
    this.cancelled = false,
  });
  
  factory AuthResult.success({required String token, required User user}) {
    return AuthResult._(success: true, token: token, user: user);
  }
  
  factory AuthResult.error(String message) {
    return AuthResult._(success: false, error: message);
  }
  
  factory AuthResult.cancelled() {
    return AuthResult._(success: false, cancelled: true);
  }
}
```

```dart
// lib/widgets/social_login_buttons.dart
import 'package:flutter/material.dart';
import 'services/social_auth_service.dart';

class SocialLoginButtons extends StatelessWidget {
  final SocialAuthService authService;
  final Function(AuthResult) onResult;
  
  const SocialLoginButtons({
    Key? key,
    required this.authService,
    required this.onResult,
  }) : super(key: key);
  
  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        _buildSocialButton(
          icon: 'assets/icons/google.png',
          label: 'Continue with Google',
          onPressed: () => _handleGoogleSignIn(),
          backgroundColor: Colors.white,
          textColor: Colors.black87,
        ),
        const SizedBox(height: 12),
        _buildSocialButton(
          icon: 'assets/icons/facebook.png',
          label: 'Continue with Facebook',
          onPressed: () => _handleFacebookSignIn(),
          backgroundColor: const Color(0xFF1877F2),
          textColor: Colors.white,
        ),
        const SizedBox(height: 12),
        _buildSocialButton(
          icon: 'assets/icons/linkedin.png',
          label: 'Continue with LinkedIn',
          onPressed: () => _handleLinkedInSignIn(),
          backgroundColor: const Color(0xFF0A66C2),
          textColor: Colors.white,
        ),
      ],
    );
  }
  
  Widget _buildSocialButton({
    required String icon,
    required String label,
    required VoidCallback onPressed,
    required Color backgroundColor,
    required Color textColor,
  }) {
    return SizedBox(
      width: double.infinity,
      height: 48,
      child: ElevatedButton.icon(
        onPressed: onPressed,
        icon: Image.asset(icon, width: 24, height: 24),
        label: Text(label),
        style: ElevatedButton.styleFrom(
          backgroundColor: backgroundColor,
          foregroundColor: textColor,
          elevation: 1,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(8),
            side: backgroundColor == Colors.white
                ? BorderSide(color: Colors.grey.shade300)
                : BorderSide.none,
          ),
        ),
      ),
    );
  }
  
  Future<void> _handleGoogleSignIn() async {
    final result = await authService.signInWithGoogle();
    onResult(result);
  }
  
  Future<void> _handleFacebookSignIn() async {
    final result = await authService.signInWithFacebook();
    onResult(result);
  }
  
  Future<void> _handleLinkedInSignIn() async {
    // LinkedIn requires web-based OAuth flow
    // Open in-app browser or external browser
    // Handle callback with deep linking
  }
}
```

---

## 4. Social Sharing

### 4.1 Share Functionality Overview

```
┌─────────────────────────────────────────────────────────────┐
│                    Product Detail Page                       │
│  ┌─────────────────────────────────────────────────────┐   │
│  │  🥛 Organic Whole Milk - 1L                         │   │
│  │  $4.99 ★★★★★ (128 reviews)                          │   │
│  │                                                     │   │
│  │  [Share]  → Opens Share Sheet                       │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                              │
│  Share Options:                                             │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐          │
│  │Facebook │ │WhatsApp │ │  Copy   │ │  More   │          │
│  └─────────┘ └─────────┘ └─────────┘ └─────────┘          │
└─────────────────────────────────────────────────────────────┘
```

### 4.2 Backend Share URL Generation

```python
# app/services/social/sharing_service.py
from urllib.parse import quote
from pydantic import BaseModel
from typing import Optional

class ShareContent(BaseModel):
    url: str
    title: str
    description: str
    image_url: Optional[str] = None
    hashtags: list[str] = []

class SocialSharingService:
    """Generate share URLs for various social platforms."""
    
    def __init__(self, base_url: str):
        self.base_url = base_url.rstrip('/')
    
    def generate_product_share_content(
        self,
        product_id: int,
        product_name: str,
        product_description: str,
        product_image: str,
        price: float
    ) -> ShareContent:
        """Generate shareable content for a product."""
        product_url = f"{self.base_url}/products/{product_id}"
        
        return ShareContent(
            url=product_url,
            title=f"{product_name} - Smart Dairy",
            description=f"Check out {product_name} for just ${price:.2f}! {product_description[:100]}...",
            image_url=product_image,
            hashtags=["SmartDairy", "FreshMilk", "DairyProducts"]
        )
    
    def get_facebook_share_url(self, content: ShareContent) -> str:
        """Generate Facebook share URL."""
        params = {
            "u": content.url,
            "quote": content.description
        }
        query = "&".join(f"{k}={quote(v)}" for k, v in params.items())
        return f"https://www.facebook.com/sharer/sharer.php?{query}"
    
    def get_twitter_share_url(self, content: ShareContent) -> str:
        """Generate X (Twitter) share URL."""
        hashtags_str = ",".join(content.hashtags)
        text = f"{content.description}\n\n{content.url}"
        
        params = {
            "text": text,
            "hashtags": hashtags_str
        }
        query = "&".join(f"{k}={quote(v)}" for k, v in params.items())
        return f"https://twitter.com/intent/tweet?{query}"
    
    def get_linkedin_share_url(self, content: ShareContent) -> str:
        """Generate LinkedIn share URL."""
        params = {
            "url": content.url,
            "title": content.title,
            "summary": content.description
        }
        query = "&".join(f"{k}={quote(v)}" for k, v in params.items())
        return f"https://www.linkedin.com/sharing/share-offsite/?{query}"
    
    def get_whatsapp_share_url(self, content: ShareContent) -> str:
        """Generate WhatsApp share URL."""
        text = f"*{content.title}*\n\n{content.description}\n\n{content.url}"
        return f"https://wa.me/?text={quote(text)}"
    
    def get_telegram_share_url(self, content: ShareContent) -> str:
        """Generate Telegram share URL."""
        text = f"{content.title}\n\n{content.description}\n\n{content.url}"
        return f"https://t.me/share/url?url={quote(content.url)}&text={quote(text)}"
    
    def get_all_share_urls(self, content: ShareContent) -> dict:
        """Get all platform share URLs."""
        return {
            "facebook": self.get_facebook_share_url(content),
            "twitter": self.get_twitter_share_url(content),
            "linkedin": self.get_linkedin_share_url(content),
            "whatsapp": self.get_whatsapp_share_url(content),
            "telegram": self.get_telegram_share_url(content),
            "native": content.url
        }

# API Endpoint
from fastapi import APIRouter

router = APIRouter(prefix="/social", tags=["Social Sharing"])

@router.get("/share/product/{product_id}")
async def get_product_share_urls(product_id: int):
    """Get all share URLs for a product."""
    # Fetch product from database
    product = await get_product(product_id)
    
    sharing_service = SocialSharingService(base_url=settings.FRONTEND_URL)
    content = sharing_service.generate_product_share_content(
        product_id=product.id,
        product_name=product.name,
        product_description=product.description,
        product_image=product.image_url,
        price=product.price
    )
    
    return {
        "product_id": product_id,
        "share_urls": sharing_service.get_all_share_urls(content),
        "content": content
    }
```

### 4.3 Flutter Share Implementation

```dart
// lib/services/sharing_service.dart
import 'package:share_plus/share_plus.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class SharingService {
  final String apiBaseUrl;
  
  SharingService({required this.apiBaseUrl});
  
  /// Share using native share sheet
  Future<void> shareProductNative(int productId) async {
    try {
      // Fetch share content from backend
      final response = await http.get(
        Uri.parse('$apiBaseUrl/social/share/product/$productId'),
      );
      
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final content = data['content'];
        
        await Share.share(
          '${content['title']}\n\n${content['description']}\n\n${content['url']}',
          subject: content['title'],
        );
      }
    } catch (e) {
      throw Exception('Failed to share product: $e');
    }
  }
  
  /// Share to specific platform
  Future<void> shareToPlatform(int productId, String platform) async {
    final response = await http.get(
      Uri.parse('$apiBaseUrl/social/share/product/$productId'),
    );
    
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      final shareUrl = data['share_urls'][platform];
      
      if (shareUrl != null) {
        final uri = Uri.parse(shareUrl);
        if (await canLaunchUrl(uri)) {
          await launchUrl(uri, mode: LaunchMode.externalApplication);
        } else {
          throw Exception('Could not launch $platform');
        }
      }
    }
  }
  
  /// Share product image with text
  Future<void> shareProductWithImage(int productId, String imagePath) async {
    final response = await http.get(
      Uri.parse('$apiBaseUrl/social/share/product/$productId'),
    );
    
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      final content = data['content'];
      
      final files = <XFile>[];
      if (imagePath.isNotEmpty) {
        files.add(XFile(imagePath));
      }
      
      await Share.shareXFiles(
        files,
        text: '${content['title']}\n\n${content['description']}\n\n${content['url']}',
        subject: content['title'],
      );
    }
  }
}

// lib/widgets/share_bottom_sheet.dart
import 'package:flutter/material.dart';
import 'services/sharing_service.dart';

class ShareBottomSheet extends StatelessWidget {
  final int productId;
  final String productName;
  final SharingService sharingService;
  
  const ShareBottomSheet({
    Key? key,
    required this.productId,
    required this.productName,
    required this.sharingService,
  }) : super(key: key);
  
  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 40,
            height: 4,
            decoration: BoxDecoration(
              color: Colors.grey.shade300,
              borderRadius: BorderRadius.circular(2),
            ),
          ),
          const SizedBox(height: 20),
          Text(
            'Share "$productName"',
            style: Theme.of(context).textTheme.titleLarge,
          ),
          const SizedBox(height: 20),
          GridView.count(
            shrinkWrap: true,
            crossAxisCount: 4,
            mainAxisSpacing: 16,
            crossAxisSpacing: 16,
            children: [
              _buildShareOption(
                icon: Icons.facebook,
                color: const Color(0xFF1877F2),
                label: 'Facebook',
                onTap: () => _shareTo(context, 'facebook'),
              ),
              _buildShareOption(
                icon: Icons.alternate_email,
                color: Colors.black,
                label: 'X / Twitter',
                onTap: () => _shareTo(context, 'twitter'),
              ),
              _buildShareOption(
                icon: Icons.business,
                color: const Color(0xFF0A66C2),
                label: 'LinkedIn',
                onTap: () => _shareTo(context, 'linkedin'),
              ),
              _buildShareOption(
                icon: Icons.chat_bubble,
                color: const Color(0xFF25D366),
                label: 'WhatsApp',
                onTap: () => _shareTo(context, 'whatsapp'),
              ),
              _buildShareOption(
                icon: Icons.send,
                color: const Color(0xFF0088CC),
                label: 'Telegram',
                onTap: () => _shareTo(context, 'telegram'),
              ),
              _buildShareOption(
                icon: Icons.copy,
                color: Colors.grey.shade700,
                label: 'Copy Link',
                onTap: () => _copyLink(context),
              ),
              _buildShareOption(
                icon: Icons.share,
                color: Colors.orange,
                label: 'More',
                onTap: () => _shareNative(context),
              ),
            ],
          ),
          const SizedBox(height: 20),
        ],
      ),
    );
  }
  
  Widget _buildShareOption({
    required IconData icon,
    required Color color,
    required String label,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 56,
            height: 56,
            decoration: BoxDecoration(
              color: color.withOpacity(0.1),
              shape: BoxShape.circle,
            ),
            child: Icon(icon, color: color, size: 28),
          ),
          const SizedBox(height: 8),
          Text(
            label,
            style: const TextStyle(fontSize: 12),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }
  
  Future<void> _shareTo(BuildContext context, String platform) async {
    try {
      await sharingService.shareToPlatform(productId, platform);
      Navigator.pop(context);
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to share: $e')),
      );
    }
  }
  
  Future<void> _shareNative(BuildContext context) async {
    try {
      await sharingService.shareProductNative(productId);
      Navigator.pop(context);
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to share: $e')),
      );
    }
  }
  
  Future<void> _copyLink(BuildContext context) async {
    // Copy link to clipboard
    // Show success message
  }
}
```

---

## 5. Facebook Integration

### 5.1 Facebook Page Management

```python
# app/services/social/facebook_page_service.py
import httpx
from datetime import datetime
from typing import List, Optional
from pydantic import BaseModel

class FacebookPost(BaseModel):
    id: str
    message: str
    created_time: datetime
    likes_count: int
    comments_count: int
    shares_count: int
    permalink_url: str

class FacebookPageService:
    """Manage Facebook Page content and analytics."""
    
    def __init__(self, access_token: str):
        self.access_token = access_token
        self.base_url = "https://graph.facebook.com/v18.0"
    
    async def get_pages(self) -> List[dict]:
        """Get list of pages managed by the user."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/me/accounts",
                params={"access_token": self.access_token}
            )
            response.raise_for_status()
            return response.json().get("data", [])
    
    async def publish_post(
        self,
        page_id: str,
        message: str,
        link: Optional[str] = None,
        scheduled_time: Optional[datetime] = None
    ) -> dict:
        """Publish or schedule a post to a Facebook page."""
        page_token = await self._get_page_access_token(page_id)
        
        params = {
            "access_token": page_token,
            "message": message
        }
        
        if link:
            params["link"] = link
        
        if scheduled_time:
            # Convert to Unix timestamp
            params["scheduled_publish_time"] = int(scheduled_time.timestamp())
            params["published"] = "false"
        
        async with httpx.AsyncClient() as client:
            response = await client.post(
                f"{self.base_url}/{page_id}/feed",
                params=params
            )
            response.raise_for_status()
            return response.json()
    
    async def get_page_insights(
        self,
        page_id: str,
        metric: str = "page_impressions,page_engaged_users,page_fans"
    ) -> dict:
        """Get page insights/analytics."""
        page_token = await self._get_page_access_token(page_id)
        
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/{page_id}/insights",
                params={
                    "access_token": page_token,
                    "metric": metric,
                    "period": "day"
                }
            )
            response.raise_for_status()
            return response.json()
    
    async def get_posts(self, page_id: str, limit: int = 25) -> List[FacebookPost]:
        """Get recent posts from a page."""
        page_token = await self._get_page_access_token(page_id)
        
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/{page_id}/posts",
                params={
                    "access_token": page_token,
                    "fields": "id,message,created_time,likes.summary(true),"
                              "comments.summary(true),shares,permalink_url",
                    "limit": limit
                }
            )
            response.raise_for_status()
            data = response.json().get("data", [])
            
            posts = []
            for item in data:
                posts.append(FacebookPost(
                    id=item["id"],
                    message=item.get("message", ""),
                    created_time=datetime.fromisoformat(
                        item["created_time"].replace("Z", "+00:00")
                    ),
                    likes_count=item.get("likes", {}).get("summary", {}).get("total_count", 0),
                    comments_count=item.get("comments", {}).get("summary", {}).get("total_count", 0),
                    shares_count=item.get("shares", {}).get("count", 0),
                    permalink_url=item.get("permalink_url", "")
                ))
            
            return posts
    
    async def _get_page_access_token(self, page_id: str) -> str:
        """Get page-specific access token."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/{page_id}",
                params={
                    "access_token": self.access_token,
                    "fields": "access_token"
                }
            )
            response.raise_for_status()
            return response.json().get("access_token", self.access_token)
```

### 5.2 Facebook Webhooks for Real-time Updates

```python
# app/api/v1/webhooks/facebook.py
from fastapi import APIRouter, Request, HTTPException, Header
from fastapi.responses import PlainTextResponse
import hmac
import hashlib
import json
from app.services.social.webhook_handler import FacebookWebhookHandler

router = APIRouter(prefix="/webhooks/facebook", tags=["Webhooks"])
webhook_handler = FacebookWebhookHandler()

@router.get("/")
async def verify_webhook(
    hub_mode: str,
    hub_verify_token: str,
    hub_challenge: str
):
    """Verify webhook subscription (GET request from Facebook)."""
    if hub_mode == "subscribe" and hub_verify_token == settings.FB_VERIFY_TOKEN:
        return PlainTextResponse(content=hub_challenge)
    raise HTTPException(status_code=403, detail="Verification failed")

@router.post("/")
async def receive_webhook(
    request: Request,
    x_hub_signature_256: str = Header(None)
):
    """Receive webhook events from Facebook."""
    body = await request.body()
    
    # Verify signature
    expected_signature = hmac.new(
        settings.FB_APP_SECRET.encode(),
        body,
        hashlib.sha256
    ).hexdigest()
    
    if not hmac.compare_digest(f"sha256={expected_signature}", x_hub_signature_256):
        raise HTTPException(status_code=401, detail="Invalid signature")
    
    data = json.loads(body)
    
    # Process different event types
    for entry in data.get("entry", []):
        for change in entry.get("changes", []):
            await webhook_handler.process_change(change)
    
    return {"status": "success"}

# app/services/social/webhook_handler.py
class FacebookWebhookHandler:
    """Handle incoming Facebook webhook events."""
    
    async def process_change(self, change: dict):
        """Process a single webhook change."""
        field = change.get("field")
        value = change.get("value", {})
        
        if field == "feed":
            await self._handle_feed_change(value)
        elif field == "mentions":
            await self._handle_mention(value)
        elif field == "ratings":
            await self._handle_rating(value)
    
    async def _handle_feed_change(self, value: dict):
        """Handle feed-related changes (posts, comments)."""
        item = value.get("item")
        verb = value.get("verb")
        
        if item == "comment" and verb == "add":
            # New comment on page
            await self._process_new_comment(value)
        elif item == "post" and verb == "add":
            # New post on page
            await self._process_new_post(value)
    
    async def _handle_rating(self, value: dict):
        """Handle new ratings/reviews."""
        review_data = {
            "reviewer_id": value.get("reviewer_id"),
            "reviewer_name": value.get("reviewer_name"),
            "rating": value.get("rating"),
            "review_text": value.get("review_text"),
            "created_time": value.get("created_time"),
            "page_id": value.get("open_graph_story", {}).get("id")
        }
        
        # Store review in database
        await store_social_review("facebook", review_data)
        
        # Trigger sentiment analysis
        await analyze_review_sentiment.delay(review_data)
    
    async def _process_new_comment(self, value: dict):
        """Process new comment for sentiment analysis and notifications."""
        comment_data = {
            "comment_id": value.get("comment_id"),
            "sender_name": value.get("sender_name"),
            "message": value.get("message"),
            "created_time": value.get("created_time")
        }
        
        # Notify relevant teams
        await notify_new_comment(comment_data)
```

---

## 6. Instagram Integration

### 6.1 Instagram Graph API Setup

**Prerequisites:**
- Facebook Business Account
- Instagram Business Account
- Facebook Page connected to Instagram account
- App with `instagram_basic` and `instagram_content_publish` permissions

### 6.2 Instagram Business API Implementation

```python
# app/services/social/instagram_service.py
import httpx
from typing import List, Optional
from pydantic import BaseModel
from datetime import datetime

class InstagramMedia(BaseModel):
    id: str
    caption: Optional[str]
    media_type: str  # IMAGE, VIDEO, CAROUSEL_ALBUM
    media_url: Optional[str]
    permalink: str
    timestamp: datetime
    like_count: int
    comments_count: int

class InstagramService:
    """Manage Instagram Business Account via Graph API."""
    
    def __init__(self, access_token: str):
        self.access_token = access_token
        self.base_url = "https://graph.facebook.com/v18.0"
    
    async def get_account_info(self, instagram_account_id: str) -> dict:
        """Get Instagram account information."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/{instagram_account_id}",
                params={
                    "access_token": self.access_token,
                    "fields": "id,username,name,profile_picture_url,"
                              "followers_count,follows_count,media_count"
                }
            )
            response.raise_for_status()
            return response.json()
    
    async def get_media(
        self,
        instagram_account_id: str,
        limit: int = 25
    ) -> List[InstagramMedia]:
        """Get media posts from Instagram account."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/{instagram_account_id}/media",
                params={
                    "access_token": self.access_token,
                    "fields": "id,caption,media_type,media_url,permalink,"
                              "timestamp,like_count,comments_count",
                    "limit": limit
                }
            )
            response.raise_for_status()
            data = response.json().get("data", [])
            
            media_list = []
            for item in data:
                media_list.append(InstagramMedia(
                    id=item["id"],
                    caption=item.get("caption"),
                    media_type=item["media_type"],
                    media_url=item.get("media_url"),
                    permalink=item["permalink"],
                    timestamp=datetime.fromisoformat(
                        item["timestamp"].replace("Z", "+00:00")
                    ),
                    like_count=item.get("like_count", 0),
                    comments_count=item.get("comments_count", 0)
                ))
            
            return media_list
    
    async def publish_photo(
        self,
        instagram_account_id: str,
        image_url: str,
        caption: str
    ) -> dict:
        """Publish a photo to Instagram."""
        # Step 1: Create media container
        async with httpx.AsyncClient() as client:
            create_response = await client.post(
                f"{self.base_url}/{instagram_account_id}/media",
                params={
                    "access_token": self.access_token,
                    "image_url": image_url,
                    "caption": caption
                }
            )
            create_response.raise_for_status()
            creation_id = create_response.json()["id"]
            
            # Step 2: Publish the container
            publish_response = await client.post(
                f"{self.base_url}/{instagram_account_id}/media_publish",
                params={
                    "access_token": self.access_token,
                    "creation_id": creation_id
                }
            )
            publish_response.raise_for_status()
            return publish_response.json()
    
    async def get_hashtag_id(self, hashtag: str) -> Optional[str]:
        """Get Instagram hashtag ID for searching."""
        # Note: Requires instagram_graph_user_profile permission
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/ig_hashtag_search",
                params={
                    "access_token": self.access_token,
                    "q": hashtag
                }
            )
            response.raise_for_status()
            data = response.json().get("data", [])
            return data[0]["id"] if data else None
    
    async def get_hashtag_media(
        self,
        hashtag_id: str,
        media_type: str = "top_media"
    ) -> List[dict]:
        """Get media for a specific hashtag."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/{hashtag_id}/{media_type}",
                params={
                    "access_token": self.access_token,
                    "fields": "id,caption,media_type,media_url,permalink"
                }
            )
            response.raise_for_status()
            return response.json().get("data", [])
```

### 6.3 Product Tagging (Limited Availability)

```python
# Note: Instagram Shopping API requires special approval
# This is a placeholder for when shopping features are available

class InstagramShoppingService:
    """Instagram Shopping and Product Tagging (Requires approval)."""
    
    async def create_product_tag(
        self,
        media_id: str,
        product_id: str,
        position: tuple[float, float]  # x, y coordinates (0.0 - 1.0)
    ):
        """Tag a product in Instagram media."""
        # Requires: instagram_shopping_tag_product permission
        # Available only to approved partners
        pass
    
    async def sync_product_catalog(self, catalog_id: str):
        """Sync product catalog with Instagram Shopping."""
        # Requires Facebook Commerce account
        pass
```

---

## 7. Google My Business

### 7.1 GMB API Integration

```python
# app/services/social/gmb_service.py
import httpx
from typing import List, Optional
from datetime import datetime
from pydantic import BaseModel

class GMBLocation(BaseModel):
    name: str
    location_key: str
    store_code: str
    primary_phone: str
    address: dict
    latlng: dict
    open_info: dict
    primary_category: str

class GMBPost(BaseModel):
    name: str
    summary: str
    call_to_action: Optional[dict]
    media: Optional[List[dict]]
    create_time: datetime
    update_time: datetime

class GoogleMyBusinessService:
    """Manage Google My Business listings."""
    
    def __init__(self, access_token: str):
        self.access_token = access_token
        self.base_url = "https://mybusinessbusinessinformation.googleapis.com/v1"
        self.posts_url = "https://mybusiness.googleapis.com/v4"
    
    async def list_accounts(self) -> List[dict]:
        """List all GMB accounts."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/accounts",
                headers={"Authorization": f"Bearer {self.access_token}"}
            )
            response.raise_for_status()
            return response.json().get("accounts", [])
    
    async def list_locations(self, account_name: str) -> List[GMBLocation]:
        """List all locations for an account."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.base_url}/{account_name}/locations",
                headers={"Authorization": f"Bearer {self.access_token}"},
                params={"readMask": "name,storeCode,title,phoneNumbers,address,latlng,openInfo,primaryCategory"}
            )
            response.raise_for_status()
            
            locations = []
            for item in response.json().get("locations", []):
                locations.append(GMBLocation(
                    name=item["name"],
                    location_key=item.get("storeCode", ""),
                    store_code=item.get("storeCode", ""),
                    primary_phone=item.get("phoneNumbers", {}).get("primaryPhone", ""),
                    address=item.get("address", {}),
                    latlng=item.get("latlng", {}),
                    open_info=item.get("openInfo", {}),
                    primary_category=item.get("primaryCategory", {}).get("displayName", "")
                ))
            
            return locations
    
    async def create_post(
        self,
        location_name: str,
        summary: str,
        media_urls: Optional[List[str]] = None,
        call_to_action: Optional[str] = None
    ) -> dict:
        """Create a post for a GMB location."""
        post_data = {
            "languageCode": "en",
            "summary": summary,
            "topicType": "STANDARD"
        }
        
        if media_urls:
            post_data["media"] = [
                {"mediaFormat": "PHOTO", "sourceUrl": url}
                for url in media_urls
            ]
        
        if call_to_action:
            post_data["callToAction"] = {
                "actionType": call_to_action,
                "url": f"https://smartdairy.com/products"
            }
        
        async with httpx.AsyncClient() as client:
            response = await client.post(
                f"{self.posts_url}/{location_name}/localPosts",
                headers={"Authorization": f"Bearer {self.access_token}"},
                json=post_data
            )
            response.raise_for_status()
            return response.json()
    
    async def get_reviews(
        self,
        location_name: str,
        page_size: int = 50
    ) -> List[dict]:
        """Get reviews for a location."""
        async with httpx.AsyncClient() as client:
            response = await client.get(
                f"{self.posts_url}/{location_name}/reviews",
                headers={"Authorization": f"Bearer {self.access_token}"},
                params={"pageSize": page_size}
            )
            response.raise_for_status()
            return response.json().get("reviews", [])
    
    async def update_review_reply(
        self,
        review_name: str,
        reply_text: str
    ) -> dict:
        """Reply to a review."""
        async with httpx.AsyncClient() as client:
            response = await client.put(
                f"{self.posts_url}/{review_name}/reply",
                headers={"Authorization": f"Bearer {self.access_token}"},
                json={"comment": reply_text}
            )
            response.raise_for_status()
            return response.json()
    
    async def update_location_info(
        self,
        location_name: str,
        updates: dict
    ) -> dict:
        """Update location information."""
        async with httpx.AsyncClient() as client:
            response = await client.patch(
                f"{self.base_url}/{location_name}",
                headers={"Authorization": f"Bearer {self.access_token}"},
                params={"updateMask": ",".join(updates.keys())},
                json=updates
            )
            response.raise_for_status()
            return response.json()
```

### 7.2 GMB Insights

```python
    async def get_insights(
        self,
        location_name: str,
        start_time: datetime,
        end_time: datetime
    ) -> dict:
        """Get insights/metrics for a location."""
        request_data = {
            "locationNames": [location_name],
            "basicRequest": {
                "metricRequests": [
                    {"metric": "ALL"}
                ],
                "timeRange": {
                    "startTime": start_time.isoformat(),
                    "endTime": end_time.isoformat()
                }
            }
        }
        
        async with httpx.AsyncClient() as client:
            response = await client.post(
                f"{self.posts_url}/{location_name}:reportInsights",
                headers={"Authorization": f"Bearer {self.access_token}"},
                json=request_data
            )
            response.raise_for_status()
            return response.json()
```

---

## 8. Social Media Analytics

### 8.1 Analytics Aggregation Service

```python
# app/services/analytics/social_analytics_service.py
from typing import Dict, List, Optional
from datetime import datetime, timedelta
from dataclasses import dataclass
from enum import Enum
import httpx

class Platform(Enum):
    FACEBOOK = "facebook"
    INSTAGRAM = "instagram"
    LINKEDIN = "linkedin"
    X = "x"
    GOOGLE_MY_BUSINESS = "gmb"

@dataclass
class MetricDataPoint:
    timestamp: datetime
    value: float
    platform: Platform
    metric_name: str

@dataclass
class AggregatedMetrics:
    platform: Platform
    period_start: datetime
    period_end: datetime
    metrics: Dict[str, List[MetricDataPoint]]
    
    # Common metrics
    impressions: int = 0
    engagements: int = 0
    reach: int = 0
    followers: int = 0
    clicks: int = 0
    shares: int = 0
    comments: int = 0
    reactions: int = 0

class SocialAnalyticsService:
    """Aggregate analytics from multiple social platforms."""
    
    def __init__(self, db_session):
        self.db = db_session
        self.platform_services = {}
    
    async def aggregate_all_platforms(
        self,
        start_date: datetime,
        end_date: datetime
    ) -> Dict[Platform, AggregatedMetrics]:
        """Fetch and aggregate metrics from all connected platforms."""
        results = {}
        
        # Get all connected accounts
        accounts = await self._get_connected_accounts()
        
        for account in accounts:
            platform = Platform(account.platform)
            
            if platform == Platform.FACEBOOK:
                metrics = await self._fetch_facebook_metrics(account, start_date, end_date)
            elif platform == Platform.INSTAGRAM:
                metrics = await self._fetch_instagram_metrics(account, start_date, end_date)
            elif platform == Platform.LINKEDIN:
                metrics = await self._fetch_linkedin_metrics(account, start_date, end_date)
            elif platform == Platform.X:
                metrics = await self._fetch_x_metrics(account, start_date, end_date)
            elif platform == Platform.GOOGLE_MY_BUSINESS:
                metrics = await self._fetch_gmb_metrics(account, start_date, end_date)
            else:
                continue
            
            results[platform] = metrics
        
        return results
    
    async def _fetch_facebook_metrics(
        self,
        account,
        start_date: datetime,
        end_date: datetime
    ) -> AggregatedMetrics:
        """Fetch Facebook page metrics."""
        from app.services.social.facebook_page_service import FacebookPageService
        
        service = FacebookPageService(account.access_token)
        insights = await service.get_page_insights(
            account.external_id,
            metric="page_impressions,page_engaged_users,page_fans,"
                   "page_actions_post_reactions_total,page_fan_adds"
        )
        
        # Parse and aggregate insights
        metrics = AggregatedMetrics(
            platform=Platform.FACEBOOK,
            period_start=start_date,
            period_end=end_date,
            metrics={}
        )
        
        for data in insights.get("data", []):
            metric_name = data["name"]
            values = data.get("values", [])
            
            if values:
                latest = values[-1]
                if metric_name == "page_impressions":
                    metrics.impressions = int(latest.get("value", 0))
                elif metric_name == "page_engaged_users":
                    metrics.engagements = int(latest.get("value", 0))
                elif metric_name == "page_fans":
                    metrics.followers = int(latest.get("value", 0))
                elif metric_name == "page_actions_post_reactions_total":
                    metrics.reactions = int(latest.get("value", 0))
        
        return metrics
    
    async def _fetch_instagram_metrics(
        self,
        account,
        start_date: datetime,
        end_date: datetime
    ) -> AggregatedMetrics:
        """Fetch Instagram account metrics."""
        from app.services.social.instagram_service import InstagramService
        
        service = InstagramService(account.access_token)
        
        # Get account info for follower count
        account_info = await service.get_account_info(account.external_id)
        
        # Get recent media for engagement metrics
        media = await service.get_media(account.external_id, limit=50)
        
        total_likes = sum(m.like_count for m in media)
        total_comments = sum(m.comments_count for m in media)
        
        return AggregatedMetrics(
            platform=Platform.INSTAGRAM,
            period_start=start_date,
            period_end=end_date,
            followers=account_info.get("followers_count", 0),
            engagements=total_likes + total_comments,
            reactions=total_likes,
            comments=total_comments,
            metrics={}
        )
    
    async def generate_cross_platform_report(
        self,
        start_date: datetime,
        end_date: datetime
    ) -> dict:
        """Generate comprehensive cross-platform analytics report."""
        platform_metrics = await self.aggregate_all_platforms(start_date, end_date)
        
        # Calculate totals
        total_followers = sum(m.followers for m in platform_metrics.values())
        total_engagements = sum(m.engagements for m in platform_metrics.values())
        total_impressions = sum(m.impressions for m in platform_metrics.values())
        
        # Calculate engagement rate
        engagement_rate = (
            (total_engagements / total_impressions * 100)
            if total_impressions > 0 else 0
        )
        
        return {
            "report_period": {
                "start": start_date.isoformat(),
                "end": end_date.isoformat()
            },
            "summary": {
                "total_followers": total_followers,
                "total_engagements": total_engagements,
                "total_impressions": total_impressions,
                "engagement_rate": round(engagement_rate, 2)
            },
            "platforms": {
                platform.value: {
                    "followers": metrics.followers,
                    "engagements": metrics.engagements,
                    "impressions": metrics.impressions,
                    "reach": metrics.reach,
                    "clicks": metrics.clicks,
                    "shares": metrics.shares,
                    "comments": metrics.comments,
                    "reactions": metrics.reactions
                }
                for platform, metrics in platform_metrics.items()
            },
            "trends": await self._calculate_trends(platform_metrics),
            "top_performing_content": await self._get_top_content(start_date, end_date)
        }
    
    async def _calculate_trends(
        self,
        current_metrics: Dict[Platform, AggregatedMetrics]
    ) -> dict:
        """Calculate week-over-week trends."""
        # Compare with previous period
        # Return percentage changes for key metrics
        return {}
    
    async def _get_top_content(
        self,
        start_date: datetime,
        end_date: datetime
    ) -> List[dict]:
        """Get top performing content across platforms."""
        # Query database for posts in date range
        # Sort by engagement metrics
        return []
```

### 8.2 Analytics Dashboard API

```python
# app/api/v1/analytics/social.py
from fastapi import APIRouter, Depends, Query
from datetime import datetime, timedelta
from typing import Optional

router = APIRouter(prefix="/analytics/social", tags=["Social Analytics"])

@router.get("/dashboard")
async def get_analytics_dashboard(
    days: int = Query(30, ge=1, le=365),
    current_user: User = Depends(get_current_user)
):
    """Get analytics dashboard data for all connected platforms."""
    end_date = datetime.utcnow()
    start_date = end_date - timedelta(days=days)
    
    service = SocialAnalyticsService(db_session)
    report = await service.generate_cross_platform_report(start_date, end_date)
    
    return report

@router.get("/platform/{platform}")
async def get_platform_analytics(
    platform: str,
    start_date: datetime = Query(...),
    end_date: datetime = Query(...),
    current_user: User = Depends(get_current_user)
):
    """Get detailed analytics for a specific platform."""
    service = SocialAnalyticsService(db_session)
    
    platform_enum = Platform(platform)
    account = await service._get_account_for_platform(platform_enum)
    
    if platform_enum == Platform.FACEBOOK:
        metrics = await service._fetch_facebook_metrics(account, start_date, end_date)
    # ... etc
    
    return metrics
```

---

## 9. Content Publishing

### 9.1 Automated Publishing Pipeline

```
┌─────────────────────────────────────────────────────────────────┐
│              Content Publishing Pipeline                         │
│                                                                  │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐      │
│  │   Product    │───>│   Content    │───>│   Schedule   │      │
│  │   Created    │    │  Generator   │    │   Queue      │      │
│  └──────────────┘    └──────────────┘    └──────┬───────┘      │
│                                                  │              │
│  ┌──────────────┐    ┌──────────────┐           │              │
│  │  Published   │<───│   Publisher  │<──────────┘              │
│  │  to Social   │    │   Service    │                          │
│  └──────────────┘    └──────────────┘                          │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 9.2 Content Generator Service

```python
# app/services/content/content_generator.py
from typing import List, Optional
from jinja2 import Template

class ContentGenerator:
    """Generate social media content from product data."""
    
    TEMPLATES = {
        "new_product": Template("""
🎉 New Arrival: {{ product.name }}!

{{ product.description[:200] }}{% if product.description|length > 200 %}...{% endif %}

💰 Price: ${{ "%.2f"|format(product.price) }}
🏷️ Category: {{ product.category }}
{% if product.organic %}🌿 100% Organic{% endif %}
{% if product.fresh %}🥛 Farm Fresh{% endif %}

Order now: {{ product.url }}

#SmartDairy #{{ product.category|replace(' ', '') }} #FreshMilk #Dairy
"""),
        
        "promotion": Template("""
🔥 Special Offer Alert!

Get {{ discount }}% off on {{ product.name }}!

Original: ~~${{ "%.2f"|format(product.original_price) }}~~
Now: ${{ "%.2f"|format(product.price) }}

⏰ Limited time only - Don't miss out!

Shop now: {{ product.url }}

#SmartDairy #Sale #SpecialOffer #DairyProducts
"""),
        
        "restock": Template("""
✅ Back in Stock!

{{ product.name }} is now available again.

We've listened to your requests and restocked this customer favorite. Order now before it sells out again!

🛒 {{ product.url }}

#SmartDairy #BackInStock #Dairy #Fresh
""")
    }
    
    def generate_product_announcement(
        self,
        product: dict,
        announcement_type: str = "new_product"
    ) -> str:
        """Generate social media post for a product."""
        template = self.TEMPLATES.get(announcement_type)
        if not template:
            raise ValueError(f"Unknown announcement type: {announcement_type}")
        
        return template.render(product=product).strip()
    
    def generate_batch_announcements(
        self,
        products: List[dict],
        announcement_type: str = "new_product"
    ) -> List[dict]:
        """Generate announcements for multiple products."""
        announcements = []
        for product in products:
            content = self.generate_product_announcement(product, announcement_type)
            announcements.append({
                "product_id": product["id"],
                "content": content,
                "image_url": product.get("image_url"),
                "platforms": product.get("auto_publish_platforms", ["facebook", "instagram"])
            })
        return announcements
    
    def optimize_for_platform(
        self,
        content: str,
        platform: str
    ) -> str:
        """Optimize content for specific platform constraints."""
        optimizations = {
            "twitter": {
                "max_length": 280,
                "hashtag_limit": 2,
            },
            "instagram": {
                "max_length": 2200,
                "hashtag_limit": 30,
            },
            "facebook": {
                "max_length": 63206,
                "hashtag_limit": 10,
            },
            "linkedin": {
                "max_length": 3000,
                "hashtag_limit": 5,
            }
        }
        
        config = optimizations.get(platform, {})
        
        if "max_length" in config and len(content) > config["max_length"]:
            content = content[:config["max_length"]-3] + "..."
        
        return content
```

### 9.3 Publishing Scheduler

```python
# app/services/content/publishing_scheduler.py
from datetime import datetime, timedelta
from typing import List, Optional
from celery import Celery
from enum import Enum

class PublishingStatus(Enum):
    PENDING = "pending"
    SCHEDULED = "scheduled"
    PUBLISHED = "published"
    FAILED = "failed"
    CANCELLED = "cancelled"

celery_app = Celery('smart_dairy')

class PublishingScheduler:
    """Schedule and manage automated social media posts."""
    
    def __init__(self, db_session):
        self.db = db_session
    
    async def schedule_product_announcement(
        self,
        product_id: int,
        platforms: List[str],
        scheduled_time: Optional[datetime] = None,
        announcement_type: str = "new_product"
    ) -> dict:
        """Schedule a product announcement for social media."""
        # Default to optimal posting time if not specified
        if not scheduled_time:
            scheduled_time = self._get_optimal_posting_time()
        
        # Create scheduled post records
        scheduled_posts = []
        for platform in platforms:
            post = ScheduledPost(
                product_id=product_id,
                platform=platform,
                scheduled_time=scheduled_time,
                status=PublishingStatus.SCHEDULED,
                announcement_type=announcement_type
            )
            self.db.add(post)
            scheduled_posts.append(post)
        
        self.db.commit()
        
        # Schedule Celery tasks
        for post in scheduled_posts:
            publish_to_social.delay(
                post_id=post.id,
                platform=post.platform,
                product_id=product_id
            )
        
        return {
            "scheduled_posts": [
                {
                    "id": post.id,
                    "platform": post.platform,
                    "scheduled_time": post.scheduled_time.isoformat()
                }
                for post in scheduled_posts
            ]
        }
    
    def _get_optimal_posting_time(self) -> datetime:
        """Calculate optimal posting time based on audience analytics."""
        # Analyze historical engagement data
        # Return time when audience is most active
        
        # Default: Next day at 10 AM
        now = datetime.utcnow()
        optimal = now + timedelta(days=1)
        return optimal.replace(hour=10, minute=0, second=0, microsecond=0)
    
    async def batch_schedule_announcements(
        self,
        products: List[int],
        platforms: List[str],
        spacing_hours: int = 4
    ) -> dict:
        """Schedule multiple product announcements with spacing."""
        base_time = self._get_optimal_posting_time()
        results = []
        
        for i, product_id in enumerate(products):
            scheduled_time = base_time + timedelta(hours=i * spacing_hours)
            result = await self.schedule_product_announcement(
                product_id=product_id,
                platforms=platforms,
                scheduled_time=scheduled_time
            )
            results.append(result)
        
        return {"scheduled_batches": results}
    
    async def cancel_scheduled_post(self, post_id: int) -> bool:
        """Cancel a scheduled post."""
        post = self.db.query(ScheduledPost).filter(ScheduledPost.id == post_id).first()
        
        if not post or post.status != PublishingStatus.SCHEDULED:
            return False
        
        # Revoke Celery task
        celery_app.control.revoke(post.celery_task_id, terminate=True)
        
        post.status = PublishingStatus.CANCELLED
        self.db.commit()
        
        return True
    
    async def get_scheduled_posts(
        self,
        platform: Optional[str] = None,
        status: Optional[PublishingStatus] = None
    ) -> List[dict]:
        """Get list of scheduled posts with filters."""
        query = self.db.query(ScheduledPost)
        
        if platform:
            query = query.filter(ScheduledPost.platform == platform)
        if status:
            query = query.filter(ScheduledPost.status == status)
        
        posts = query.order_by(ScheduledPost.scheduled_time.desc()).all()
        
        return [
            {
                "id": post.id,
                "product_id": post.product_id,
                "platform": post.platform,
                "scheduled_time": post.scheduled_time.isoformat(),
                "status": post.status.value,
                "published_url": post.published_url
            }
            for post in posts
        ]

# Celery task for publishing
@celery_app.task(bind=True, max_retries=3)
def publish_to_social(self, post_id: int, platform: str, product_id: int):
    """Celery task to publish content to social media."""
    from app.services.content.content_generator import ContentGenerator
    from app.services.social.publisher_factory import get_publisher
    
    try:
        # Fetch product details
        product = get_product(product_id)
        
        # Generate content
        generator = ContentGenerator()
        content = generator.generate_product_announcement(product)
        optimized_content = generator.optimize_for_platform(content, platform)
        
        # Get platform publisher
        publisher = get_publisher(platform)
        
        # Publish
        result = publisher.publish(
            content=optimized_content,
            image_url=product.get("image_url")
        )
        
        # Update post status
        update_scheduled_post(post_id, status="published", published_url=result.get("url"))
        
        return result
        
    except Exception as exc:
        # Retry with exponential backoff
        retry_count = self.request.retries
        if retry_count < 3:
            raise self.retry(exc=exc, countdown=60 * (2 ** retry_count))
        
        # Mark as failed after max retries
        update_scheduled_post(post_id, status="failed", error=str(exc))
        raise
```

### 9.4 Platform Publisher Factory

```python
# app/services/social/publisher_factory.py
def get_publisher(platform: str):
    """Get the appropriate publisher for a platform."""
    publishers = {
        "facebook": FacebookPublisher,
        "instagram": InstagramPublisher,
        "linkedin": LinkedInPublisher,
        "twitter": TwitterPublisher,
    }
    
    publisher_class = publishers.get(platform)
    if not publisher_class:
        raise ValueError(f"Unsupported platform: {platform}")
    
    return publisher_class()

class FacebookPublisher:
    def publish(self, content: str, image_url: Optional[str] = None):
        # Use Facebook Page Service
        pass

class InstagramPublisher:
    def publish(self, content: str, image_url: str):
        # Use Instagram Service
        if not image_url:
            raise ValueError("Instagram requires an image")
        pass

class LinkedInPublisher:
    def publish(self, content: str, image_url: Optional[str] = None):
        pass

class TwitterPublisher:
    def publish(self, content: str, image_url: Optional[str] = None):
        pass
```

---

## 10. Review Management

### 10.1 Review Aggregation Service

```python
# app/services/reviews/review_aggregator.py
from typing import List, Dict, Optional
from datetime import datetime
from enum import Enum
from pydantic import BaseModel

class ReviewPlatform(Enum):
    GOOGLE_MY_BUSINESS = "gmb"
    FACEBOOK = "facebook"
    YELP = "yelp"
    TRUSTPILOT = "trustpilot"

class ReviewSentiment(Enum):
    VERY_NEGATIVE = 1
    NEGATIVE = 2
    NEUTRAL = 3
    POSITIVE = 4
    VERY_POSITIVE = 5

class UnifiedReview(BaseModel):
    id: str
    platform: ReviewPlatform
    author_name: str
    author_avatar: Optional[str]
    rating: float  # 1-5 scale
    review_text: str
    created_at: datetime
    sentiment: ReviewSentiment
    sentiment_score: float  # 0.0 to 1.0
    response_text: Optional[str]
    responded_at: Optional[datetime]
    helpful_count: int
    source_url: str
    tags: List[str] = []

class ReviewAggregator:
    """Aggregate reviews from multiple platforms."""
    
    def __init__(self, db_session, sentiment_analyzer):
        self.db = db_session
        self.sentiment_analyzer = sentiment_analyzer
    
    async def fetch_all_reviews(
        self,
        since: Optional[datetime] = None
    ) -> List[UnifiedReview]:
        """Fetch and unify reviews from all connected platforms."""
        all_reviews = []
        
        # Fetch from each platform
        tasks = [
            self._fetch_gmb_reviews(since),
            self._fetch_facebook_reviews(since),
            self._fetch_yelp_reviews(since),
        ]
        
        results = await asyncio.gather(*tasks, return_exceptions=True)
        
        for result in results:
            if isinstance(result, list):
                all_reviews.extend(result)
        
        # Sort by date (newest first)
        all_reviews.sort(key=lambda r: r.created_at, reverse=True)
        
        return all_reviews
    
    async def _fetch_gmb_reviews(
        self,
        since: Optional[datetime]
    ) -> List[UnifiedReview]:
        """Fetch reviews from Google My Business."""
        from app.services.social.gmb_service import GoogleMyBusinessService
        
        service = GoogleMyBusinessService(self._get_access_token("gmb"))
        
        # Get all locations
        locations = await service.list_locations(self.account_name)
        
        reviews = []
        for location in locations:
            gmb_reviews = await service.get_reviews(location.name)
            
            for review in gmb_reviews:
                sentiment = self.sentiment_analyzer.analyze(review.get("comment", ""))
                
                unified = UnifiedReview(
                    id=f"gmb_{review['reviewId']}",
                    platform=ReviewPlatform.GOOGLE_MY_BUSINESS,
                    author_name=review.get("reviewer", {}).get("displayName", "Anonymous"),
                    author_avatar=review.get("reviewer", {}).get("profilePhotoUrl"),
                    rating=review.get("starRating", "FIVE_STARS").split("_")[0],
                    review_text=review.get("comment", ""),
                    created_at=datetime.fromisoformat(review["createTime"].replace("Z", "+00:00")),
                    sentiment=sentiment.label,
                    sentiment_score=sentiment.score,
                    response_text=review.get("reviewReply", {}).get("comment"),
                    responded_at=review.get("reviewReply", {}).get("updateTime"),
                    helpful_count=review.get("totalNumberOfReviews", 0),
                    source_url=review.get("name", "")
                )
                reviews.append(unified)
        
        return reviews
    
    async def _fetch_facebook_reviews(
        self,
        since: Optional[datetime]
    ) -> List[UnifiedReview]:
        """Fetch recommendations from Facebook."""
        # Facebook uses "recommendations" instead of reviews
        # Similar implementation to GMB
        return []
    
    async def _fetch_yelp_reviews(
        self,
        since: Optional[datetime]
    ) -> List[UnifiedReview]:
        """Fetch reviews from Yelp."""
        # Requires Yelp Fusion API
        return []
    
    def calculate_aggregate_rating(self, reviews: List[UnifiedReview]) -> dict:
        """Calculate aggregate rating statistics."""
        if not reviews:
            return {"average": 0, "total": 0, "distribution": {}}
        
        ratings = [r.rating for r in reviews]
        
        distribution = {i: 0 for i in range(1, 6)}
        for r in ratings:
            distribution[int(r)] = distribution.get(int(r), 0) + 1
        
        return {
            "average": round(sum(ratings) / len(ratings), 2),
            "total": len(reviews),
            "distribution": distribution,
            "by_platform": self._group_by_platform(reviews)
        }
    
    def _group_by_platform(self, reviews: List[UnifiedReview]) -> dict:
        """Group reviews by platform."""
        grouped = {}
        for review in reviews:
            platform = review.platform.value
            if platform not in grouped:
                grouped[platform] = {"count": 0, "average": 0, "sum": 0}
            grouped[platform]["count"] += 1
            grouped[platform]["sum"] += review.rating
        
        for platform in grouped:
            grouped[platform]["average"] = round(
                grouped[platform]["sum"] / grouped[platform]["count"], 2
            )
            del grouped[platform]["sum"]
        
        return grouped
```

### 10.2 Sentiment Analysis Integration

```python
# app/services/reviews/sentiment_analyzer.py
from transformers import pipeline
from typing import Dict
from enum import Enum

class SentimentResult:
    def __init__(self, label: str, score: float):
        self.label = label
        self.score = score

class SentimentAnalyzer:
    """Analyze sentiment of review text using ML model."""
    
    def __init__(self):
        # Use a pre-trained sentiment analysis model
        # Can be replaced with cloud service (AWS Comprehend, Google NLP)
        self.classifier = pipeline(
            "sentiment-analysis",
            model="distilbert-base-uncased-finetuned-sst-2-english"
        )
    
    def analyze(self, text: str) -> SentimentResult:
        """Analyze sentiment of a single review."""
        if not text or not text.strip():
            return SentimentResult("NEUTRAL", 0.5)
        
        result = self.classifier(text[:512])[0]  # Model has token limit
        
        # Map to unified sentiment scale
        label_map = {
            "NEGATIVE": "NEGATIVE",
            "POSITIVE": "POSITIVE"
        }
        
        return SentimentResult(
            label=label_map.get(result["label"], "NEUTRAL"),
            score=result["score"]
        )
    
    def batch_analyze(self, texts: list) -> list:
        """Analyze sentiment for multiple texts."""
        results = self.classifier(texts)
        return [
            SentimentResult(r["label"], r["score"])
            for r in results
        ]
    
    def extract_keywords(self, text: str) -> list:
        """Extract key topics/keywords from review text."""
        # Simple keyword extraction
        # Can be enhanced with NER or topic modeling
        keywords = []
        
        dairy_keywords = [
            "fresh", "organic", "taste", "quality", "delivery",
            "packaging", "price", "customer service", "temperature"
        ]
        
        text_lower = text.lower()
        for keyword in dairy_keywords:
            if keyword in text_lower:
                keywords.append(keyword)
        
        return keywords
```

### 10.3 Review Response Management

```python
# app/services/reviews/response_manager.py
from typing import Optional
from datetime import datetime
from jinja2 import Template

class ReviewResponseManager:
    """Manage responses to customer reviews."""
    
    RESPONSE_TEMPLATES = {
        "positive": Template("""
Thank you so much for your wonderful review, {{ name }}! 🙏

We're thrilled to hear that you enjoyed our {{ product }}. Your feedback means the world to us and motivates our team to keep delivering the freshest dairy products.

We look forward to serving you again soon!

Best regards,
The Smart Dairy Team 🥛
"""),
        
        "neutral": Template("""
Hi {{ name }}, thank you for taking the time to share your feedback.

We appreciate your honest review and are always looking for ways to improve. If you have any specific suggestions or concerns, please don't hesitate to reach out to us directly at support@smartdairy.com.

We hope to have the opportunity to serve you better next time!

Best regards,
The Smart Dairy Team
"""),
        
        "negative": Template("""
Dear {{ name }},

We sincerely apologize that your experience with us didn't meet your expectations. This is certainly not the standard we strive for at Smart Dairy.

We would love the opportunity to make this right. Please contact our customer care team at support@smartdairy.com or call us at 1-800-SMART-DAIRY so we can address your concerns personally.

Thank you for giving us the chance to improve.

Sincerely,
The Smart Dairy Team
""")
    }
    
    def generate_response(
        self,
        review: UnifiedReview,
        custom_message: Optional[str] = None
    ) -> str:
        """Generate a response to a review."""
        if custom_message:
            return custom_message
        
        # Determine template based on rating
        if review.rating >= 4:
            template_key = "positive"
        elif review.rating >= 3:
            template_key = "neutral"
        else:
            template_key = "negative"
        
        template = self.RESPONSE_TEMPLATES[template_key]
        
        return template.render(
            name=review.author_name.split()[0],  # First name only
            product="products"  # Could be extracted from review text
        ).strip()
    
    async def publish_response(
        self,
        review: UnifiedReview,
        response_text: str
    ) -> bool:
        """Publish response to the original platform."""
        if review.platform == ReviewPlatform.GOOGLE_MY_BUSINESS:
            return await self._respond_to_gmb(review, response_text)
        elif review.platform == ReviewPlatform.FACEBOOK:
            return await self._respond_to_facebook(review, response_text)
        # ... etc
        
        return False
    
    async def _respond_to_gmb(
        self,
        review: UnifiedReview,
        response_text: str
    ) -> bool:
        """Post response to Google My Business."""
        from app.services.social.gmb_service import GoogleMyBusinessService
        
        service = GoogleMyBusinessService(self._get_access_token("gmb"))
        
        try:
            await service.update_review_reply(review.source_url, response_text)
            return True
        except Exception as e:
            logger.error(f"Failed to respond to GMB review: {e}")
            return False
```

### 10.4 Review Webhook Handler

```python
# app/api/v1/webhooks/reviews.py
from fastapi import APIRouter, Request, BackgroundTasks

router = APIRouter(prefix="/webhooks/reviews", tags=["Review Webhooks"])

@router.post("/google")
async def google_review_webhook(
    request: Request,
    background_tasks: BackgroundTasks
):
    """Handle Google review notifications."""
    data = await request.json()
    
    # Verify webhook signature
    # ...
    
    # Process in background
    background_tasks.add_task(process_new_review, "gmb", data)
    
    return {"status": "received"}

@router.post("/facebook")
async def facebook_review_webhook(request: Request):
    """Handle Facebook recommendation notifications."""
    # Similar implementation
    pass

async def process_new_review(platform: str, data: dict):
    """Process a new review notification."""
    # 1. Store review in database
    # 2. Analyze sentiment
    # 3. Send notification to relevant team
    # 4. Auto-generate response suggestion
    # 5. Flag for review if sentiment is very negative
    
    review_data = extract_review_data(platform, data)
    
    # Store
    review = await store_review(review_data)
    
    # Analyze
    sentiment = sentiment_analyzer.analyze(review_data["text"])
    
    # Notify
    if sentiment.label == "VERY_NEGATIVE":
        await notify_urgent_review(review)
    
    # Generate response suggestion
    suggestion = response_manager.generate_response(review)
    await store_response_suggestion(review.id, suggestion)
```

---

## 11. Implementation

### 11.1 Project Structure

```
smart_dairy/
├── app/
│   ├── api/
│   │   ├── v1/
│   │   │   ├── auth.py              # Social login endpoints
│   │   │   ├── social/
│   │   │   │   ├── __init__.py
│   │   │   │   ├── sharing.py       # Share URL generation
│   │   │   │   ├── publishing.py    # Content publishing
│   │   │   │   └── analytics.py     # Analytics endpoints
│   │   │   └── webhooks/
│   │   │       ├── facebook.py
│   │   │       └── reviews.py
│   │   └── deps.py
│   ├── services/
│   │   ├── auth/
│   │   │   ├── google_auth.py
│   │   │   ├── facebook_auth.py
│   │   │   └── linkedin_auth.py
│   │   ├── social/
│   │   │   ├── facebook_page_service.py
│   │   │   ├── instagram_service.py
│   │   │   ├── gmb_service.py
│   │   │   ├── sharing_service.py
│   │   │   └── webhook_handler.py
│   │   ├── content/
│   │   │   ├── content_generator.py
│   │   │   └── publishing_scheduler.py
│   │   ├── reviews/
│   │   │   ├── review_aggregator.py
│   │   │   ├── sentiment_analyzer.py
│   │   │   └── response_manager.py
│   │   └── analytics/
│   │       └── social_analytics_service.py
│   ├── models/
│   │   ├── social_account.py
│   │   ├── scheduled_post.py
│   │   ├── social_review.py
│   │   └── user.py
│   └── core/
│       ├── config.py
│       └── celery.py
├── flutter_app/
│   └── lib/
│       ├── services/
│       │   ├── social_auth_service.dart
│       │   └── sharing_service.dart
│       └── widgets/
│           └── social_login_buttons.dart
└── celery_tasks/
    └── publishing_tasks.py
```

### 11.2 Database Models

```python
# app/models/social_models.py
from sqlalchemy import Column, Integer, String, DateTime, ForeignKey, JSON, Enum
from sqlalchemy.orm import relationship
from datetime import datetime
import enum

class Platform(enum.Enum):
    FACEBOOK = "facebook"
    INSTAGRAM = "instagram"
    LINKEDIN = "linkedin"
    GOOGLE = "google"
    TWITTER = "twitter"
    GMB = "gmb"

class SocialAccount(Base):
    __tablename__ = "social_accounts"
    
    id = Column(Integer, primary_key=True)
    user_id = Column(Integer, ForeignKey("users.id"))
    platform = Column(Enum(Platform))
    external_id = Column(String(255))
    username = Column(String(255))
    access_token = Column(String(1024))
    refresh_token = Column(String(1024))
    token_expires_at = Column(DateTime)
    scope = Column(JSON)
    is_active = Column(Boolean, default=True)
    metadata = Column(JSON)
    created_at = Column(DateTime, default=datetime.utcnow)
    updated_at = Column(DateTime, onupdate=datetime.utcnow)
    
    user = relationship("User", back_populates="social_accounts")

class ScheduledPost(Base):
    __tablename__ = "scheduled_posts"
    
    id = Column(Integer, primary_key=True)
    product_id = Column(Integer, ForeignKey("products.id"))
    platform = Column(Enum(Platform))
    content = Column(Text)
    media_urls = Column(JSON)
    scheduled_time = Column(DateTime)
    published_time = Column(DateTime)
    status = Column(String(50))  # pending, scheduled, published, failed, cancelled
    published_url = Column(String(1024))
    celery_task_id = Column(String(255))
    error_message = Column(Text)
    created_at = Column(DateTime, default=datetime.utcnow)
    
    product = relationship("Product")

class SocialReview(Base):
    __tablename__ = "social_reviews"
    
    id = Column(Integer, primary_key=True)
    platform = Column(Enum(Platform))
    external_id = Column(String(255), unique=True)
    author_name = Column(String(255))
    author_avatar = Column(String(1024))
    rating = Column(Float)
    review_text = Column(Text)
    sentiment_score = Column(Float)
    sentiment_label = Column(String(50))
    keywords = Column(JSON)
    response_text = Column(Text)
    responded_at = Column(DateTime)
    helpful_count = Column(Integer, default=0)
    source_url = Column(String(1024))
    raw_data = Column(JSON)
    fetched_at = Column(DateTime, default=datetime.utcnow)
```

### 11.3 Environment Configuration

```python
# app/core/config.py
from pydantic_settings import BaseSettings
from typing import List

class Settings(BaseSettings):
    # Application
    APP_NAME: str = "Smart Dairy API"
    DEBUG: bool = False
    
    # Database
    DATABASE_URL: str
    
    # Redis (for Celery)
    REDIS_URL: str = "redis://localhost:6379/0"
    
    # JWT
    JWT_SECRET: str
    JWT_ALGORITHM: str = "HS256"
    JWT_EXPIRATION_HOURS: int = 24
    
    # Frontend URL
    FRONTEND_URL: str = "https://smartdairy.com"
    
    # Google OAuth
    GOOGLE_CLIENT_ID: str
    GOOGLE_CLIENT_SECRET: str
    GOOGLE_REDIRECT_URI: str = "https://api.smartdairy.com/api/v1/auth/google/callback"
    
    # Facebook OAuth
    FACEBOOK_APP_ID: str
    FACEBOOK_APP_SECRET: str
    FACEBOOK_REDIRECT_URI: str = "https://api.smartdairy.com/api/v1/auth/facebook/callback"
    FACEBOOK_VERIFY_TOKEN: str
    
    # LinkedIn OAuth
    LINKEDIN_CLIENT_ID: str
    LINKEDIN_CLIENT_SECRET: str
    LINKEDIN_REDIRECT_URI: str = "https://api.smartdairy.com/api/v1/auth/linkedin/callback"
    
    # X (Twitter) API
    TWITTER_API_KEY: str
    TWITTER_API_SECRET: str
    TWITTER_BEARER_TOKEN: str
    
    # Instagram
    INSTAGRAM_APP_ID: str
    INSTAGRAM_APP_SECRET: str
    
    # Google My Business
    GMB_CLIENT_ID: str
    GMB_CLIENT_SECRET: str
    
    # Yelp
    YELP_API_KEY: str
    
    # Celery
    CELERY_BROKER_URL: str
    CELERY_RESULT_BACKEND: str
    
    class Config:
        env_file = ".env"

settings = Settings()
```

### 11.4 Celery Configuration

```python
# app/core/celery.py
from celery import Celery
from app.core.config import settings

celery_app = Celery(
    "smart_dairy",
    broker=settings.CELERY_BROKER_URL,
    backend=settings.CELERY_RESULT_BACKEND,
    include=[
        "app.tasks.publishing",
        "app.tasks.analytics",
        "app.tasks.reviews",
    ]
)

celery_app.conf.update(
    task_serializer="json",
    accept_content=["json"],
    result_serializer="json",
    timezone="UTC",
    enable_utc=True,
    task_track_started=True,
    task_time_limit=30 * 60,  # 30 minutes
    worker_prefetch_multiplier=1,
    task_acks_late=True,
)
```

---

## 12. Privacy & Compliance

### 12.1 Data Handling Policy

| Data Type | Storage | Retention | Encryption |
|-----------|---------|-----------|------------|
| OAuth Tokens | Database | Until revoked | AES-256 at rest |
| User Profile | Database | Account lifetime | AES-256 at rest |
| Social Content | Database | 2 years | AES-256 at rest |
| Analytics | Data Warehouse | 5 years | AES-256 at rest |
| Review Data | Database | 7 years | AES-256 at rest |

### 12.2 OAuth Scope Management

**Minimum Required Scopes:**

| Platform | Scope | Purpose |
|----------|-------|---------|
| Google | `openid email profile` | Authentication |
| Google | `https://www.googleapis.com/auth/business.manage` | GMB management |
| Facebook | `email public_profile` | Authentication |
| Facebook | `pages_manage_posts pages_read_engagement` | Page management |
| Instagram | `instagram_basic` | Basic account access |
| Instagram | `instagram_content_publish` | Publishing posts |
| LinkedIn | `openid profile email` | Authentication |
| LinkedIn | `w_member_social` | Share posts |

### 12.3 User Consent Requirements

```python
# app/services/privacy/consent_manager.py
class ConsentManager:
    """Manage user consent for social media integrations."""
    
    REQUIRED_CONSENTS = {
        "social_login": {
            "description": "Sign in using your social media account",
            "data_usage": ["email", "profile picture", "name"],
            "retention": "Account lifetime"
        },
        "social_sharing": {
            "description": "Share content to your social media profiles",
            "data_usage": ["User-generated content", "Share activity"],
            "retention": "2 years"
        },
        "social_analytics": {
            "description": "Collect anonymized social media metrics",
            "data_usage": ["Engagement data", "Aggregate statistics"],
            "retention": "5 years"
        },
        "review_sync": {
            "description": "Sync and display reviews from social platforms",
            "data_usage": ["Review content", "Reviewer information"],
            "retention": "7 years"
        }
    }
    
    def record_consent(self, user_id: int, consent_type: str, granted: bool):
        """Record user consent choice."""
        consent = UserConsent(
            user_id=user_id,
            consent_type=consent_type,
            granted=granted,
            timestamp=datetime.utcnow(),
            ip_address=request.client.host,
            user_agent=request.headers.get("user-agent")
        )
        db.add(consent)
        db.commit()
    
    def verify_consent(self, user_id: int, consent_type: str) -> bool:
        """Verify if user has granted consent."""
        consent = db.query(UserConsent).filter(
            UserConsent.user_id == user_id,
            UserConsent.consent_type == consent_type,
            UserConsent.granted == True
        ).order_by(UserConsent.timestamp.desc()).first()
        
        return consent is not None
    
    def revoke_consent(self, user_id: int, consent_type: str):
        """Revoke previously granted consent."""
        # Record revocation
        self.record_consent(user_id, consent_type, False)
        
        # Trigger data deletion if required
        if consent_type == "social_login":
            self._disconnect_social_accounts(user_id)
```

### 12.4 GDPR Compliance Checklist

| Requirement | Implementation |
|-------------|----------------|
| Right to Access | `/api/v1/user/data-export` endpoint |
| Right to Erasure | `/api/v1/user/delete-account` with cascade delete |
| Data Portability | JSON export of all social data |
| Consent Records | Immutable audit log of all consent changes |
| Data Breach Notification | Automated alerts within 72 hours |
| Privacy by Design | Minimal data collection, encryption at rest |

### 12.5 Data Deletion Procedures

```python
# app/services/privacy/data_deletion.py
async def delete_user_social_data(user_id: int):
    """Delete all social media related data for a user."""
    
    # 1. Revoke OAuth tokens
    accounts = db.query(SocialAccount).filter(SocialAccount.user_id == user_id).all()
    for account in accounts:
        await revoke_oauth_token(account.platform, account.access_token)
    
    # 2. Delete from database
    db.query(SocialAccount).filter(SocialAccount.user_id == user_id).delete()
    db.query(ScheduledPost).filter(ScheduledPost.user_id == user_id).delete()
    db.query(UserConsent).filter(UserConsent.user_id == user_id).delete()
    
    # 3. Delete from analytics warehouse
    await delete_analytics_data(user_id)
    
    # 4. Cancel pending tasks
    for account in accounts:
        revoke_celery_tasks(f"social_{account.platform}_{user_id}")
    
    db.commit()
    
    # 5. Log deletion for audit
    await log_data_deletion(user_id, "social_data")
```

---

## 13. Appendices

### Appendix A: OAuth Scopes Reference

#### Google OAuth Scopes

| Scope | Description | Risk Level |
|-------|-------------|------------|
| `openid` | OpenID Connect authentication | Low |
| `email` | User email address | Low |
| `profile` | Basic profile information | Low |
| `https://www.googleapis.com/auth/business.manage` | GMB management | Medium |

#### Facebook Permissions

| Permission | Description | Use Case |
|------------|-------------|----------|
| `email` | Access user's email | Authentication |
| `public_profile` | Name, profile photo | Authentication |
| `pages_manage_posts` | Publish to pages | Content publishing |
| `pages_read_engagement` | Read page insights | Analytics |
| `instagram_basic` | Read Instagram account | Account linking |
| `instagram_content_publish` | Publish to Instagram | Content publishing |

#### LinkedIn Scopes

| Scope | Description |
|-------|-------------|
| `openid` | OpenID Connect |
| `profile` | Basic profile |
| `email` | Email address |
| `w_member_social` | Post on behalf of user |
| `r_organization_social` | Read organization posts |
| `w_organization_social` | Post as organization |

### Appendix B: Error Handling

```python
# app/exceptions/social_exceptions.py
class SocialMediaException(Exception):
    """Base exception for social media integration."""
    pass

class OAuthException(SocialMediaException):
    """OAuth flow errors."""
    def __init__(self, message: str, error_code: str = None):
        super().__init__(message)
        self.error_code = error_code

class RateLimitException(SocialMediaException):
    """API rate limit exceeded."""
    def __init__(self, platform: str, retry_after: int):
        super().__init__(f"Rate limit exceeded for {platform}")
        self.platform = platform
        self.retry_after = retry_after

class TokenExpiredException(SocialMediaException):
    """OAuth token has expired."""
    pass

class PlatformAPIException(SocialMediaException):
    """Generic platform API error."""
    def __init__(self, platform: str, status_code: int, response: dict):
        super().__init__(f"{platform} API error: {status_code}")
        self.platform = platform
        self.status_code = status_code
        self.response = response

# Error mapping
PLATFORM_ERROR_CODES = {
    "facebook": {
        "1": "Unknown error",
        "2": "Service temporarily unavailable",
        "4": "API rate limit exceeded",
        "10": "Application does not have permission",
        "102": "Session expired",
        "190": "Access token expired"
    },
    "google": {
        "invalid_grant": "Authorization code expired or invalid",
        "invalid_client": "Client authentication failed",
        "unauthorized_client": "Client not authorized for this grant type"
    }
}
```

### Appendix C: Testing Guide

```python
# tests/integration/test_social_auth.py
import pytest
from fastapi.testclient import TestClient

@pytest.fixture
def mock_google_oauth():
    """Mock Google OAuth responses."""
    with responses.RequestsMock() as rsps:
        # Mock token endpoint
        rsps.add(
            responses.POST,
            "https://oauth2.googleapis.com/token",
            json={
                "access_token": "mock_access_token",
                "id_token": "mock_id_token",
                "expires_in": 3600
            }
        )
        
        # Mock userinfo endpoint
        rsps.add(
            responses.GET,
            "https://www.googleapis.com/oauth2/v3/userinfo",
            json={
                "sub": "123456789",
                "email": "test@example.com",
                "name": "Test User",
                "picture": "https://example.com/photo.jpg"
            }
        )
        
        yield rsps

def test_google_callback_success(client: TestClient, mock_google_oauth):
    """Test successful Google OAuth callback."""
    response = client.get("/api/v1/auth/google/callback?code=auth_code&state=xyz")
    
    assert response.status_code == 200
    data = response.json()
    assert "access_token" in data
    assert data["token_type"] == "bearer"

def test_google_callback_invalid_state(client: TestClient):
    """Test Google OAuth with invalid state."""
    response = client.get("/api/v1/auth/google/callback?code=auth_code&state=invalid")
    
    assert response.status_code == 400
    assert "invalid state" in response.json()["detail"].lower()

# tests/integration/test_social_sharing.py
def test_generate_share_urls(client: TestClient, test_product):
    """Test share URL generation for a product."""
    response = client.get(f"/api/v1/social/share/product/{test_product.id}")
    
    assert response.status_code == 200
    data = response.json()
    
    assert "share_urls" in data
    assert "facebook" in data["share_urls"]
    assert "twitter" in data["share_urls"]
    assert "linkedin" in data["share_urls"]
    assert "whatsapp" in data["share_urls"]
    
    # Verify URLs are properly formatted
    assert data["share_urls"]["facebook"].startswith("https://www.facebook.com/sharer/")
    assert data["share_urls"]["twitter"].startswith("https://twitter.com/intent/tweet")
```

### Appendix D: Rate Limiting Strategy

```python
# app/services/rate_limiter.py
from redis import Redis
from datetime import datetime, timedelta

class PlatformRateLimiter:
    """Handle rate limiting for social media APIs."""
    
    RATE_LIMITS = {
        "facebook": {
            "requests_per_window": 200,
            "window_seconds": 3600,
            "user_based": True
        },
        "instagram": {
            "requests_per_window": 200,
            "window_seconds": 3600,
            "user_based": True
        },
        "linkedin": {
            "requests_per_window": 500,
            "window_seconds": 86400,
            "user_based": False
        },
        "google": {
            "requests_per_window": 10000,
            "window_seconds": 86400,
            "user_based": False
        }
    }
    
    def __init__(self, redis_client: Redis):
        self.redis = redis_client
    
    def is_allowed(self, platform: str, user_id: str = None) -> bool:
        """Check if request is within rate limit."""
        config = self.RATE_LIMITS.get(platform)
        if not config:
            return True
        
        key = f"ratelimit:{platform}"
        if config["user_based"] and user_id:
            key = f"{key}:{user_id}"
        
        current = self.redis.get(key)
        if current is None:
            self.redis.setex(key, config["window_seconds"], 1)
            return True
        
        if int(current) >= config["requests_per_window"]:
            return False
        
        self.redis.incr(key)
        return True
    
    def get_retry_after(self, platform: str, user_id: str = None) -> int:
        """Get seconds until rate limit resets."""
        config = self.RATE_LIMITS.get(platform)
        key = f"ratelimit:{platform}"
        if config["user_based"] and user_id:
            key = f"{key}:{user_id}"
        
        ttl = self.redis.ttl(key)
        return max(ttl, 0)
```

### Appendix E: Monitoring & Alerting

```python
# app/services/monitoring/social_monitoring.py
from prometheus_client import Counter, Histogram, Gauge
import logging

# Metrics
social_api_requests = Counter(
    'social_api_requests_total',
    'Total social API requests',
    ['platform', 'endpoint', 'status']
)

social_api_latency = Histogram(
    'social_api_latency_seconds',
    'Social API request latency',
    ['platform', 'endpoint']
)

oauth_tokens_active = Gauge(
    'oauth_tokens_active',
    'Number of active OAuth tokens',
    ['platform']
)

scheduled_posts_pending = Gauge(
    'scheduled_posts_pending',
    'Number of pending scheduled posts',
    ['platform']
)

class SocialMonitoring:
    """Monitor social media integration health."""
    
    def __init__(self):
        self.logger = logging.getLogger(__name__)
    
    def record_api_call(self, platform: str, endpoint: str, status: str, latency: float):
        """Record API call metrics."""
        social_api_requests.labels(
            platform=platform,
            endpoint=endpoint,
            status=status
        ).inc()
        
        social_api_latency.labels(
            platform=platform,
            endpoint=endpoint
        ).observe(latency)
    
    def check_token_health(self):
        """Check for tokens expiring soon."""
        # Query tokens expiring within 7 days
        expiring_tokens = db.query(SocialAccount).filter(
            SocialAccount.token_expires_at < datetime.utcnow() + timedelta(days=7),
            SocialAccount.is_active == True
        ).all()
        
        for token in expiring_tokens:
            self.logger.warning(
                f"Token expiring soon: {token.platform} for user {token.user_id}"
            )
            # Send alert to admin
            send_alert(f"Token expiring for {token.platform}")
    
    def check_failed_posts(self):
        """Alert on failed scheduled posts."""
        failed_posts = db.query(ScheduledPost).filter(
            ScheduledPost.status == "failed",
            ScheduledPost.created_at > datetime.utcnow() - timedelta(hours=24)
        ).count()
        
        if failed_posts > 10:
            send_alert(f"High number of failed posts: {failed_posts}")
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Integration Engineer | _________________ | _______ |
| Owner | Tech Lead | _________________ | _______ |
| Reviewer | CTO | _________________ | _______ |

---

**End of Document E-013: Social Media Integration Guide**

*This document is confidential and proprietary to Smart Dairy Ltd. Unauthorized distribution is prohibited.*
