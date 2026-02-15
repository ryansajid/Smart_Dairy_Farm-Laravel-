# MILESTONE 3: CUSTOMER MOBILE APP DEVELOPMENT

## Smart Dairy Smart Portal + ERP System Implementation - Phase 2

---

| Attribute | Details |
|-----------|---------|
| **Milestone** | Phase 2 - Milestone 3 |
| **Title** | Customer Mobile App Development |
| **Duration** | Days 121-130 (10 Working Days) |
| **Phase** | Phase 2 - Operations |
| **Version** | 1.0 |
| **Status** | Draft |

---

## TABLE OF CONTENTS

1. [Milestone Overview](#1-milestone-overview)
2. [Authentication & Onboarding](#2-authentication--onboarding)
3. [Product Catalog & Discovery](#3-product-catalog--discovery)
4. [Shopping Cart & Checkout](#4-shopping-cart--checkout)
5. [Order Management](#5-order-management)
6. [Subscription Management](#6-subscription-management)
7. [Loyalty Program UI](#7-loyalty-program-ui)
8. [Profile & Settings](#8-profile--settings)
9. [Appendices](#9-appendices)

---

## 1. MILESTONE OVERVIEW

### 1.1 Objectives

| Objective ID | Description | Success Criteria |
|--------------|-------------|------------------|
| M3-OBJ-001 | Complete authentication UI | Login, register, OTP flow |
| M3-OBJ-002 | Product catalog browsing | Categories, search, filters |
| M3-OBJ-003 | Shopping cart & checkout | Full purchase flow |
| M3-OBJ-004 | Order tracking | Order history, tracking |
| M3-OBJ-005 | Subscription management | Create, modify, pause subscriptions |
| M3-OBJ-006 | Loyalty program interface | Points, rewards, tier status |
| M3-OBJ-007 | User profile | Addresses, payment methods, preferences |

### 1.2 UI Design System

```dart
// lib/core/theme/app_theme.dart

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class AppTheme {
  // Primary Colors - Brand Identity
  static const Color primaryColor = Color(0xFF1E88E5);      // Dairy Blue
  static const Color primaryDark = Color(0xFF1565C0);       // Dark Blue
  static const Color primaryLight = Color(0xFF64B5F6);      // Light Blue
  
  // Secondary Colors
  static const Color accentColor = Color(0xFF43A047);       // Fresh Green
  static const Color accentLight = Color(0xFF81C784);       // Light Green
  static const Color warningColor = Color(0xFFFFA726);      // Orange
  static const Color errorColor = Color(0xFFE53935);        // Red
  static const Color successColor = Color(0xFF43A047);      // Green
  
  // Neutral Colors
  static const Color background = Color(0xFFF5F7FA);
  static const Color surface = Color(0xFFFFFFFF);
  static const Color textPrimary = Color(0xFF212121);
  static const Color textSecondary = Color(0xFF757575);
  static const Color textTertiary = Color(0xFF9E9E9E);
  static const Color divider = Color(0xFFE0E0E0);
  
  // Gradient Backgrounds
  static const LinearGradient primaryGradient = LinearGradient(
    colors: [primaryColor, primaryDark],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );
  
  static const LinearGradient cardGradient = LinearGradient(
    colors: [Colors.white, Color(0xFFF8F9FA)],
    begin: Alignment.topCenter,
    end: Alignment.bottomCenter,
  );
  
  // Typography
  static TextStyle get heading1 => TextStyle(
    fontSize: 28.sp,
    fontWeight: FontWeight.bold,
    color: textPrimary,
    letterSpacing: -0.5,
  );
  
  static TextStyle get heading2 => TextStyle(
    fontSize: 24.sp,
    fontWeight: FontWeight.bold,
    color: textPrimary,
    letterSpacing: -0.5,
  );
  
  static TextStyle get heading3 => TextStyle(
    fontSize: 20.sp,
    fontWeight: FontWeight.w600,
    color: textPrimary,
  );
  
  static TextStyle get subtitle1 => TextStyle(
    fontSize: 16.sp,
    fontWeight: FontWeight.w600,
    color: textPrimary,
  );
  
  static TextStyle get subtitle2 => TextStyle(
    fontSize: 14.sp,
    fontWeight: FontWeight.w500,
    color: textSecondary,
  );
  
  static TextStyle get body1 => TextStyle(
    fontSize: 16.sp,
    fontWeight: FontWeight.normal,
    color: textPrimary,
    height: 1.5,
  );
  
  static TextStyle get body2 => TextStyle(
    fontSize: 14.sp,
    fontWeight: FontWeight.normal,
    color: textSecondary,
    height: 1.5,
  );
  
  static TextStyle get caption => TextStyle(
    fontSize: 12.sp,
    fontWeight: FontWeight.normal,
    color: textTertiary,
  );
  
  static TextStyle get button => TextStyle(
    fontSize: 16.sp,
    fontWeight: FontWeight.w600,
    color: Colors.white,
    letterSpacing: 0.5,
  );
  
  static TextStyle get price => TextStyle(
    fontSize: 18.sp,
    fontWeight: FontWeight.bold,
    color: primaryColor,
  );
  
  static TextStyle get priceOriginal => TextStyle(
    fontSize: 14.sp,
    fontWeight: FontWeight.normal,
    color: textTertiary,
    decoration: TextDecoration.lineThrough,
  );
  
  // Shadows
  static List<BoxShadow> get cardShadow => [
    BoxShadow(
      color: Colors.black.withOpacity(0.05),
      blurRadius: 10,
      offset: const Offset(0, 4),
    ),
  ];
  
  static List<BoxShadow> get elevatedShadow => [
    BoxShadow(
      color: Colors.black.withOpacity(0.1),
      blurRadius: 20,
      offset: const Offset(0, 8),
    ),
  ];
  
  // Border Radius
  static double get smallRadius => 8.r;
  static double get mediumRadius => 12.r;
  static double get largeRadius => 16.r;
  static double get xlargeRadius => 24.r;
  static double get roundRadius => 100.r;
  
  // Spacing
  static double get xs => 4.w;
  static double get sm => 8.w;
  static double get md => 16.w;
  static double get lg => 24.w;
  static double get xl => 32.w;
  static double get xxl => 48.w;
  
  // Theme Data
  static ThemeData get lightTheme => ThemeData(
    useMaterial3: true,
    primaryColor: primaryColor,
    scaffoldBackgroundColor: background,
    colorScheme: const ColorScheme.light(
      primary: primaryColor,
      secondary: accentColor,
      surface: surface,
      background: background,
      error: errorColor,
      onPrimary: Colors.white,
      onSecondary: Colors.white,
      onSurface: textPrimary,
      onBackground: textPrimary,
    ),
    appBarTheme: AppBarTheme(
      backgroundColor: surface,
      foregroundColor: textPrimary,
      elevation: 0,
      centerTitle: true,
      titleTextStyle: heading3,
    ),
    cardTheme: CardTheme(
      elevation: 0,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(mediumRadius),
      ),
      color: surface,
    ),
    elevatedButtonTheme: ElevatedButtonThemeData(
      style: ElevatedButton.styleFrom(
        backgroundColor: primaryColor,
        foregroundColor: Colors.white,
        padding: EdgeInsets.symmetric(horizontal: 24.w, vertical: 16.h),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(mediumRadius),
        ),
        textStyle: button,
        elevation: 0,
      ),
    ),
    outlinedButtonTheme: OutlinedButtonThemeData(
      style: OutlinedButton.styleFrom(
        foregroundColor: primaryColor,
        side: const BorderSide(color: primaryColor, width: 1.5),
        padding: EdgeInsets.symmetric(horizontal: 24.w, vertical: 16.h),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(mediumRadius),
        ),
      ),
    ),
    textButtonTheme: TextButtonThemeData(
      style: TextButton.styleFrom(
        foregroundColor: primaryColor,
        textStyle: subtitle1.copyWith(color: primaryColor),
      ),
    ),
    inputDecorationTheme: InputDecorationTheme(
      filled: true,
      fillColor: surface,
      contentPadding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 16.h),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(mediumRadius),
        borderSide: const BorderSide(color: divider),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(mediumRadius),
        borderSide: const BorderSide(color: divider),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(mediumRadius),
        borderSide: const BorderSide(color: primaryColor, width: 2),
      ),
      errorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(mediumRadius),
        borderSide: const BorderSide(color: errorColor),
      ),
      labelStyle: body2,
      hintStyle: body2.copyWith(color: textTertiary),
    ),
    bottomNavigationBarTheme: BottomNavigationBarThemeData(
      backgroundColor: surface,
      selectedItemColor: primaryColor,
      unselectedItemColor: textTertiary,
      type: BottomNavigationBarType.fixed,
      selectedLabelStyle: caption.copyWith(fontWeight: FontWeight.w600),
      unselectedLabelStyle: caption,
      elevation: 8,
    ),
    tabBarTheme: TabBarTheme(
      labelColor: primaryColor,
      unselectedLabelColor: textSecondary,
      indicatorColor: primaryColor,
      labelStyle: subtitle1,
      unselectedLabelStyle: subtitle1.copyWith(color: textSecondary),
    ),
    chipTheme: ChipThemeData(
      backgroundColor: background,
      selectedColor: primaryColor.withOpacity(0.1),
      labelStyle: body2,
      padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 8.h),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(smallRadius),
      ),
    ),
    snackBarTheme: SnackBarThemeData(
      behavior: SnackBarBehavior.floating,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(mediumRadius),
      ),
      contentTextStyle: body1.copyWith(color: Colors.white),
    ),
    dividerTheme: const DividerThemeData(
      color: divider,
      thickness: 1,
      space: 1,
    ),
  );
}

// Extension for quick access
extension ThemeExtension on BuildContext {
  ThemeData get theme => Theme.of(this);
  TextTheme get textTheme => theme.textTheme;
  ColorScheme get colors => theme.colorScheme;
}
```

---

## 2. AUTHENTICATION & ONBOARDING

### 2.1 Splash Screen

```dart
// lib/presentation/pages/splash/splash_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:smart_dairy_mobile/core/router/route_names.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/presentation/blocs/auth/auth_bloc.dart';

class SplashPage extends StatefulWidget {
  const SplashPage({super.key});
  
  @override
  State<SplashPage> createState() => _SplashPageState();
}

class _SplashPageState extends State<SplashPage> 
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _fadeAnimation;
  late Animation<double> _scaleAnimation;
  
  @override
  void initState() {
    super.initState();
    
    _controller = AnimationController(
      duration: const Duration(milliseconds: 1500),
      vsync: this,
    );
    
    _fadeAnimation = Tween<double>(begin: 0, end: 1).animate(
      CurvedAnimation(
        parent: _controller,
        curve: const Interval(0, 0.5, curve: Curves.easeIn),
      ),
    );
    
    _scaleAnimation = Tween<double>(begin: 0.8, end: 1).animate(
      CurvedAnimation(
        parent: _controller,
        curve: const Interval(0.2, 0.7, curve: Curves.easeOutBack),
      ),
    );
    
    _controller.forward();
    
    // Check auth status after animation
    Future.delayed(const Duration(seconds: 2), () {
      if (mounted) {
        context.read<AuthBloc>().add(const AuthEvent.authCheckRequested());
      }
    });
  }
  
  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }
  
  @override
  Widget build(BuildContext context) {
    return BlocListener<AuthBloc, AuthState>(
      listener: (context, state) {
        state.whenOrNull(
          authenticated: (_) => context.go(RouteNames.home),
          unauthenticated: () => context.go(RouteNames.onboarding),
        );
      },
      child: Scaffold(
        backgroundColor: AppTheme.primaryColor,
        body: Center(
          child: AnimatedBuilder(
            animation: _controller,
            builder: (context, child) {
              return FadeTransition(
                opacity: _fadeAnimation,
                child: ScaleTransition(
                  scale: _scaleAnimation,
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      // Logo
                      Container(
                        width: 120.w,
                        height: 120.w,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(AppTheme.xlargeRadius),
                          boxShadow: AppTheme.elevatedShadow,
                        ),
                        child: Center(
                          child: Icon(
                            Icons.local_drink,
                            size: 60.w,
                            color: AppTheme.primaryColor,
                          ),
                        ),
                      ),
                      SizedBox(height: 24.h),
                      // App Name
                      Text(
                        'Smart Dairy',
                        style: TextStyle(
                          fontSize: 32.sp,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                          letterSpacing: 1,
                        ),
                      ),
                      SizedBox(height: 8.h),
                      // Tagline
                      Text(
                        'Fresh Milk, Delivered Daily',
                        style: TextStyle(
                          fontSize: 16.sp,
                          color: Colors.white.withOpacity(0.8),
                          letterSpacing: 0.5,
                        ),
                      ),
                      SizedBox(height: 48.h),
                      // Loading indicator
                      SizedBox(
                        width: 32.w,
                        height: 32.w,
                        child: CircularProgressIndicator(
                          valueColor: AlwaysStoppedAnimation<Color>(
                            Colors.white.withOpacity(0.8),
                          ),
                          strokeWidth: 3,
                        ),
                      ),
                    ],
                  ),
                ),
              );
            },
          ),
        ),
      ),
    );
  }
}
```

### 2.2 Onboarding Screens

```dart
// lib/presentation/pages/onboarding/onboarding_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';
import 'package:smart_dairy_mobile/core/router/route_names.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';

class OnboardingPage extends StatefulWidget {
  const OnboardingPage({super.key});
  
  @override
  State<OnboardingPage> createState() => _OnboardingPageState();
}

class _OnboardingPageState extends State<OnboardingPage> {
  final PageController _pageController = PageController();
  int _currentPage = 0;
  
  final List<OnboardingItem> _items = [
    OnboardingItem(
      image: 'assets/images/onboarding_1.png',
      title: 'Fresh Farm Milk',
      description: 'Get the freshest milk directly from local farms to your doorstep every morning.',
    ),
    OnboardingItem(
      image: 'assets/images/onboarding_2.png',
      title: 'Flexible Subscriptions',
      description: 'Subscribe to daily, alternate day, or weekly deliveries as per your need.',
    ),
    OnboardingItem(
      image: 'assets/images/onboarding_3.png',
      title: 'Earn Rewards',
      description: 'Collect loyalty points with every purchase and redeem exciting rewards.',
    ),
  ];
  
  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }
  
  void _onNextPressed() {
    if (_currentPage < _items.length - 1) {
      _pageController.nextPage(
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeInOut,
      );
    } else {
      context.go(RouteNames.login);
    }
  }
  
  void _onSkipPressed() {
    context.go(RouteNames.login);
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      body: SafeArea(
        child: Column(
          children: [
            // Skip button
            Align(
              alignment: Alignment.topRight,
              child: Padding(
                padding: EdgeInsets.all(AppTheme.md),
                child: TextButton(
                  onPressed: _onSkipPressed,
                  child: Text(
                    'Skip',
                    style: AppTheme.subtitle1.copyWith(color: AppTheme.textSecondary),
                  ),
                ),
              ),
            ),
            
            // Page view
            Expanded(
              child: PageView.builder(
                controller: _pageController,
                onPageChanged: (index) {
                  setState(() {
                    _currentPage = index;
                  });
                },
                itemCount: _items.length,
                itemBuilder: (context, index) {
                  return _buildOnboardingPage(_items[index]);
                },
              ),
            ),
            
            // Bottom section
            Container(
              padding: EdgeInsets.all(AppTheme.lg),
              child: Column(
                children: [
                  // Page indicator
                  SmoothPageIndicator(
                    controller: _pageController,
                    count: _items.length,
                    effect: ExpandingDotsEffect(
                      activeDotColor: AppTheme.primaryColor,
                      dotColor: AppTheme.divider,
                      dotHeight: 8.h,
                      dotWidth: 8.w,
                      expansionFactor: 3,
                      spacing: 8.w,
                    ),
                  ),
                  SizedBox(height: 32.h),
                  
                  // Next/Get Started button
                  SizedBox(
                    width: double.infinity,
                    height: 56.h,
                    child: ElevatedButton(
                      onPressed: _onNextPressed,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.primaryColor,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(AppTheme.mediumRadius),
                        ),
                      ),
                      child: Text(
                        _currentPage == _items.length - 1 ? 'Get Started' : 'Next',
                        style: AppTheme.button,
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
  
  Widget _buildOnboardingPage(OnboardingItem item) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppTheme.lg),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          // Image
          Container(
            width: 280.w,
            height: 280.w,
            decoration: BoxDecoration(
              color: AppTheme.primaryColor.withOpacity(0.1),
              borderRadius: BorderRadius.circular(AppTheme.xlargeRadius),
            ),
            child: Center(
              child: Image.asset(
                item.image,
                width: 200.w,
                height: 200.w,
                fit: BoxFit.contain,
              ),
            ),
          ),
          SizedBox(height: 48.h),
          
          // Title
          Text(
            item.title,
            style: AppTheme.heading2,
            textAlign: TextAlign.center,
          ),
          SizedBox(height: 16.h),
          
          // Description
          Text(
            item.description,
            style: AppTheme.body1.copyWith(color: AppTheme.textSecondary),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }
}

class OnboardingItem {
  final String image;
  final String title;
  final String description;
  
  OnboardingItem({
    required this.image,
    required this.title,
    required this.description,
  });
}
```

### 2.3 Login Screen

```dart
// lib/presentation/pages/auth/login_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:smart_dairy_mobile/core/router/route_names.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/core/utils/validators.dart';
import 'package:smart_dairy_mobile/presentation/blocs/auth/auth_bloc.dart';
import 'package:smart_dairy_mobile/presentation/widgets/common/app_button.dart';
import 'package:smart_dairy_mobile/presentation/widgets/common/app_text_field.dart';
import 'package:smart_dairy_mobile/presentation/widgets/common/loading_overlay.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});
  
  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  final _phoneController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;
  bool _rememberMe = false;
  
  @override
  void dispose() {
    _phoneController.dispose();
    _passwordController.dispose();
    super.dispose();
  }
  
  void _onLoginPressed() {
    if (_formKey.currentState?.validate() ?? false) {
      context.read<AuthBloc>().add(
        AuthEvent.loginRequested(
          phone: _phoneController.text.trim(),
          password: _passwordController.text,
        ),
      );
    }
  }
  
  void _onRegisterPressed() {
    context.push(RouteNames.register);
  }
  
  void _onForgotPasswordPressed() {
    context.push(RouteNames.forgotPassword);
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      body: BlocConsumer<AuthBloc, AuthState>(
        listener: (context, state) {
          state.whenOrNull(
            authenticated: (_) => context.go(RouteNames.home),
            error: (message) {
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text(message),
                  backgroundColor: AppTheme.errorColor,
                  behavior: SnackBarBehavior.floating,
                ),
              );
            },
          );
        },
        builder: (context, state) {
          return LoadingOverlay(
            isLoading: state is AuthLoading,
            child: SafeArea(
              child: SingleChildScrollView(
                padding: EdgeInsets.all(AppTheme.lg),
                child: Form(
                  key: _formKey,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SizedBox(height: 32.h),
                      
                      // Welcome back
                      Text(
                        'Welcome Back!',
                        style: AppTheme.heading1,
                      ),
                      SizedBox(height: 8.h),
                      Text(
                        'Sign in to continue',
                        style: AppTheme.body1.copyWith(
                          color: AppTheme.textSecondary,
                        ),
                      ),
                      SizedBox(height: 48.h),
                      
                      // Phone field
                      AppTextField(
                        controller: _phoneController,
                        label: 'Phone Number',
                        hint: '017XXXXXXXX',
                        prefixIcon: Icons.phone_outlined,
                        keyboardType: TextInputType.phone,
                        validator: Validators.validatePhone,
                      ),
                      SizedBox(height: 20.h),
                      
                      // Password field
                      AppTextField(
                        controller: _passwordController,
                        label: 'Password',
                        hint: 'Enter your password',
                        prefixIcon: Icons.lock_outline,
                        suffixIcon: _obscurePassword
                            ? Icons.visibility_off
                            : Icons.visibility,
                        onSuffixIconPressed: () {
                          setState(() {
                            _obscurePassword = !_obscurePassword;
                          });
                        },
                        obscureText: _obscurePassword,
                        validator: Validators.validatePassword,
                      ),
                      SizedBox(height: 16.h),
                      
                      // Remember me & Forgot password
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Row(
                            children: [
                              Checkbox(
                                value: _rememberMe,
                                onChanged: (value) {
                                  setState(() {
                                    _rememberMe = value ?? false;
                                  });
                                },
                                activeColor: AppTheme.primaryColor,
                              ),
                              Text(
                                'Remember me',
                                style: AppTheme.body2,
                              ),
                            ],
                          ),
                          TextButton(
                            onPressed: _onForgotPasswordPressed,
                            child: Text(
                              'Forgot Password?',
                              style: AppTheme.subtitle2.copyWith(
                                color: AppTheme.primaryColor,
                              ),
                            ),
                          ),
                        ],
                      ),
                      SizedBox(height: 32.h),
                      
                      // Login button
                      AppButton(
                        text: 'Login',
                        onPressed: _onLoginPressed,
                        isFullWidth: true,
                      ),
                      SizedBox(height: 32.h),
                      
                      // Divider
                      Row(
                        children: [
                          Expanded(child: Divider(color: AppTheme.divider)),
                          Padding(
                            padding: EdgeInsets.symmetric(horizontal: 16.w),
                            child: Text(
                              'OR',
                              style: AppTheme.caption,
                            ),
                          ),
                          Expanded(child: Divider(color: AppTheme.divider)),
                        ],
                      ),
                      SizedBox(height: 32.h),
                      
                      // Social login buttons
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          _buildSocialButton(
                            icon: Icons.g_mobiledata,
                            onPressed: () {
                              // Google login
                            },
                          ),
                          SizedBox(width: 16.w),
                          _buildSocialButton(
                            icon: Icons.facebook,
                            onPressed: () {
                              // Facebook login
                            },
                          ),
                        ],
                      ),
                      SizedBox(height: 48.h),
                      
                      // Register link
                      Center(
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Text(
                              'Don\'t have an account? ',
                              style: AppTheme.body1,
                            ),
                            TextButton(
                              onPressed: _onRegisterPressed,
                              child: Text(
                                'Sign Up',
                                style: AppTheme.subtitle1.copyWith(
                                  color: AppTheme.primaryColor,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          );
        },
      ),
    );
  }
  
  Widget _buildSocialButton({
    required IconData icon,
    required VoidCallback onPressed,
  }) {
    return InkWell(
      onTap: onPressed,
      borderRadius: BorderRadius.circular(AppTheme.mediumRadius),
      child: Container(
        width: 56.w,
        height: 56.w,
        decoration: BoxDecoration(
          color: AppTheme.surface,
          borderRadius: BorderRadius.circular(AppTheme.mediumRadius),
          border: Border.all(color: AppTheme.divider),
        ),
        child: Icon(
          icon,
          size: 28.w,
          color: AppTheme.textPrimary,
        ),
      ),
    );
  }
}
```

---

## 3. PRODUCT CATALOG & DISCOVERY

### 3.1 Home Page

```dart
// lib/presentation/pages/home/home_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:smart_dairy_mobile/core/router/route_names.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/presentation/blocs/cart/cart_bloc.dart';
import 'package:smart_dairy_mobile/presentation/blocs/product/product_bloc.dart';
import 'package:smart_dairy_mobile/presentation/widgets/common/search_bar.dart';
import 'home_banner.dart';
import 'home_categories.dart';
import 'home_products.dart';
import 'home_subscriptions.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});
  
  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  final ScrollController _scrollController = ScrollController();
  
  @override
  void initState() {
    super.initState();
    context.read<ProductBloc>().add(const ProductEvent.productsLoaded());
    context.read<CartBloc>().add(const CartEvent.cartInitialized());
  }
  
  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      body: CustomScrollView(
        controller: _scrollController,
        slivers: [
          // App bar
          SliverAppBar(
            expandedHeight: 60.h,
            floating: true,
            pinned: true,
            elevation: 0,
            backgroundColor: AppTheme.background,
            title: Row(
              children: [
                Container(
                  width: 40.w,
                  height: 40.w,
                  decoration: BoxDecoration(
                    color: AppTheme.primaryColor,
                    borderRadius: BorderRadius.circular(AppTheme.smallRadius),
                  ),
                  child: Icon(
                    Icons.local_drink,
                    color: Colors.white,
                    size: 24.w,
                  ),
                ),
                SizedBox(width: 12.w),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Smart Dairy',
                      style: AppTheme.subtitle1,
                    ),
                    Text(
                      'Delivery to: Mirpur, Dhaka',
                      style: AppTheme.caption.copyWith(
                        color: AppTheme.primaryColor,
                      ),
                    ),
                  ],
                ),
              ],
            ),
            actions: [
              // Notifications
              IconButton(
                onPressed: () {
                  context.push(RouteNames.notifications);
                },
                icon: Stack(
                  children: [
                    Icon(
                      Icons.notifications_outlined,
                      color: AppTheme.textPrimary,
                      size: 24.w,
                    ),
                    Positioned(
                      right: 0,
                      top: 0,
                      child: Container(
                        width: 8.w,
                        height: 8.w,
                        decoration: const BoxDecoration(
                          color: AppTheme.errorColor,
                          shape: BoxShape.circle,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              // Cart
              BlocBuilder<CartBloc, CartState>(
                builder: (context, state) {
                  final itemCount = state.maybeWhen(
                    loaded: (cart) => cart.itemCount,
                    orElse: () => 0,
                  );
                  
                  return IconButton(
                    onPressed: () {
                      context.push(RouteNames.cart);
                    },
                    icon: Stack(
                      children: [
                        Icon(
                          Icons.shopping_bag_outlined,
                          color: AppTheme.textPrimary,
                          size: 24.w,
                        ),
                        if (itemCount > 0)
                          Positioned(
                            right: 0,
                            top: 0,
                            child: Container(
                              padding: EdgeInsets.all(2.w),
                              decoration: const BoxDecoration(
                                color: AppTheme.primaryColor,
                                shape: BoxShape.circle,
                              ),
                              child: Text(
                                itemCount.toString(),
                                style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 10.sp,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ),
                      ],
                    ),
                  );
                },
              ),
              SizedBox(width: 8.w),
            ],
          ),
          
          // Content
          SliverToBoxAdapter(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                SizedBox(height: 16.h),
                
                // Search bar
                Padding(
                  padding: EdgeInsets.symmetric(horizontal: AppTheme.lg),
                  child: AppSearchBar(
                    hint: 'Search products...',
                    onSubmitted: (query) {
                      context.push(
                        RouteNames.search,
                        extra: {'query': query},
                      );
                    },
                  ),
                ),
                SizedBox(height: 24.h),
                
                // Banner carousel
                const HomeBanner(),
                SizedBox(height: 24.h),
                
                // Categories
                const HomeCategories(),
                SizedBox(height: 24.h),
                
                // Featured subscriptions
                const HomeSubscriptions(),
                SizedBox(height: 24.h),
                
                // Popular products
                const HomeProducts(),
                SizedBox(height: 32.h),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
```

### 3.2 Product Card Widget

```dart
// lib/presentation/widgets/product/product_card.dart

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:shimmer/shimmer.dart';
import 'package:smart_dairy_mobile/core/router/route_names.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/data/models/product/product_model.dart';
import 'package:smart_dairy_mobile/presentation/blocs/cart/cart_bloc.dart';

class ProductCard extends StatelessWidget {
  final ProductModel product;
  final VoidCallback? onTap;
  
  const ProductCard({
    super.key,
    required this.product,
    this.onTap,
  });
  
  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap ?? () {
        context.push(RouteNames.productDetail, extra: product);
      },
      child: Container(
        width: 160.w,
        decoration: BoxDecoration(
          color: AppTheme.surface,
          borderRadius: BorderRadius.circular(AppTheme.mediumRadius),
          boxShadow: AppTheme.cardShadow,
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image
            Stack(
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.only(
                    topLeft: Radius.circular(AppTheme.mediumRadius),
                    topRight: Radius.circular(AppTheme.mediumRadius),
                  ),
                  child: CachedNetworkImage(
                    imageUrl: product.imageUrl ?? '',
                    width: 160.w,
                    height: 120.w,
                    fit: BoxFit.cover,
                    placeholder: (context, url) => Shimmer.fromColors(
                      baseColor: Colors.grey[300]!,
                      highlightColor: Colors.grey[100]!,
                      child: Container(
                        width: 160.w,
                        height: 120.w,
                        color: Colors.white,
                      ),
                    ),
                    errorWidget: (context, url, error) => Container(
                      width: 160.w,
                      height: 120.w,
                      color: AppTheme.background,
                      child: Icon(
                        Icons.image_not_supported,
                        color: AppTheme.textTertiary,
                        size: 40.w,
                      ),
                    ),
                  ),
                ),
                
                // Discount badge
                if (product.isOnSale)
                  Positioned(
                    top: 8.h,
                    left: 8.w,
                    child: Container(
                      padding: EdgeInsets.symmetric(
                        horizontal: 8.w,
                        vertical: 4.h,
                      ),
                      decoration: BoxDecoration(
                        color: AppTheme.errorColor,
                        borderRadius: BorderRadius.circular(AppTheme.smallRadius),
                      ),
                      child: Text(
                        '-${product.discountPercentage.toInt()}%',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 12.sp,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                
                // Subscription badge
                if (product.isSubscriptionEligible)
                  Positioned(
                    top: 8.h,
                    right: 8.w,
                    child: Container(
                      padding: EdgeInsets.all(4.w),
                      decoration: BoxDecoration(
                        color: AppTheme.accentColor,
                        shape: BoxShape.circle,
                      ),
                      child: Icon(
                        Icons.repeat,
                        color: Colors.white,
                        size: 14.w,
                      ),
                    ),
                  ),
              ],
            ),
            
            // Content
            Padding(
              padding: EdgeInsets.all(12.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Category
                  Text(
                    product.category,
                    style: AppTheme.caption.copyWith(
                      color: AppTheme.primaryColor,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  SizedBox(height: 4.h),
                  
                  // Name
                  Text(
                    product.name,
                    style: AppTheme.subtitle1,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  SizedBox(height: 8.h),
                  
                  // Price
                  Row(
                    children: [
                      Text(
                        '৳${product.effectivePrice.toStringAsFixed(0)}',
                        style: AppTheme.price,
                      ),
                      if (product.isOnSale) ...[
                        SizedBox(width: 8.w),
                        Text(
                          '৳${product.price.toStringAsFixed(0)}',
                          style: AppTheme.priceOriginal,
                        ),
                      ],
                    ],
                  ),
                  SizedBox(height: 4.h),
                  
                  // UOM
                  Text(
                    'per ${product.uom}',
                    style: AppTheme.caption,
                  ),
                  SizedBox(height: 12.h),
                  
                  // Add to cart button
                  SizedBox(
                    width: double.infinity,
                    height: 36.h,
                    child: ElevatedButton.icon(
                      onPressed: () {
                        context.read<CartBloc>().add(
                          CartEvent.itemAdded(
                            product: product,
                            quantity: 1,
                          ),
                        );
                        
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(
                            content: Text('${product.name} added to cart'),
                            duration: const Duration(seconds: 2),
                            action: SnackBarAction(
                              label: 'View Cart',
                              onPressed: () {
                                context.push(RouteNames.cart);
                              },
                            ),
                          ),
                        );
                      },
                      icon: Icon(Icons.add, size: 18.w),
                      label: Text(
                        'Add',
                        style: TextStyle(fontSize: 14.sp),
                      ),
                      style: ElevatedButton.styleFrom(
                        padding: EdgeInsets.zero,
                        minimumSize: Size.zero,
                        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
```

---

## 4. SHOPPING CART & CHECKOUT

### 4.1 Cart Page

```dart
// lib/presentation/pages/cart/cart_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:smart_dairy_mobile/core/router/route_names.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/presentation/blocs/cart/cart_bloc.dart';
import 'cart_item_tile.dart';
import 'promo_code_section.dart';
import 'cart_summary.dart';

class CartPage extends StatelessWidget {
  const CartPage({super.key});
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      appBar: AppBar(
        title: const Text('Shopping Cart'),
        actions: [
          BlocBuilder<CartBloc, CartState>(
            builder: (context, state) {
              return state.maybeWhen(
                loaded: (cart) => cart.isEmpty
                    ? const SizedBox.shrink()
                    : TextButton(
                        onPressed: () {
                          showDialog(
                            context: context,
                            builder: (context) => AlertDialog(
                              title: const Text('Clear Cart?'),
                              content: const Text(
                                'Are you sure you want to remove all items from your cart?',
                              ),
                              actions: [
                                TextButton(
                                  onPressed: () => Navigator.pop(context),
                                  child: const Text('Cancel'),
                                ),
                                TextButton(
                                  onPressed: () {
                                    context.read<CartBloc>().add(
                                      const CartEvent.cartCleared(),
                                    );
                                    Navigator.pop(context);
                                  },
                                  child: const Text(
                                    'Clear',
                                    style: TextStyle(color: AppTheme.errorColor),
                                  ),
                                ),
                              ],
                            ),
                          );
                        },
                        child: const Text('Clear'),
                      ),
                orElse: () => const SizedBox.shrink(),
              );
            },
          ),
        ],
      ),
      body: BlocBuilder<CartBloc, CartState>(
        builder: (context, state) {
          return state.when(
            initial: () => const Center(child: CircularProgressIndicator()),
            loading: () => const Center(child: CircularProgressIndicator()),
            loaded: (cart) => cart.isEmpty
                ? _buildEmptyCart(context)
                : _buildCartContent(context, cart),
            error: (message) => Center(
              child: Text('Error: $message'),
            ),
          );
        },
      ),
      bottomNavigationBar: BlocBuilder<CartBloc, CartState>(
        builder: (context, state) {
          return state.maybeWhen(
            loaded: (cart) => cart.isEmpty
                ? const SizedBox.shrink()
                : Container(
                    padding: EdgeInsets.all(AppTheme.lg),
                    decoration: BoxDecoration(
                      color: AppTheme.surface,
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.05),
                          blurRadius: 10,
                          offset: const Offset(0, -4),
                        ),
                      ],
                    ),
                    child: SafeArea(
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    'Total:',
                                    style: AppTheme.body2,
                                  ),
                                  Text(
                                    '৳${cart.total.toStringAsFixed(2)}',
                                    style: AppTheme.heading3.copyWith(
                                      color: AppTheme.primaryColor,
                                    ),
                                  ),
                                ],
                              ),
                              SizedBox(
                                width: 180.w,
                                height: 50.h,
                                child: ElevatedButton(
                                  onPressed: () {
                                    context.push(RouteNames.checkout);
                                  },
                                  child: const Text('Checkout'),
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                  ),
            orElse: () => const SizedBox.shrink(),
          );
        },
      ),
    );
  }
  
  Widget _buildEmptyCart(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.shopping_cart_outlined,
            size: 100.w,
            color: AppTheme.textTertiary,
          ),
          SizedBox(height: 24.h),
          Text(
            'Your cart is empty',
            style: AppTheme.heading3,
          ),
          SizedBox(height: 8.h),
          Text(
            'Add some fresh dairy products!',
            style: AppTheme.body1.copyWith(color: AppTheme.textSecondary),
          ),
          SizedBox(height: 32.h),
          ElevatedButton(
            onPressed: () {
              context.go(RouteNames.home);
            },
            child: const Text('Start Shopping'),
          ),
        ],
      ),
    );
  }
  
  Widget _buildCartContent(BuildContext context, CartModel cart) {
    return SingleChildScrollView(
      padding: EdgeInsets.all(AppTheme.lg),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Cart items count
          Text(
            '${cart.itemCount} items',
            style: AppTheme.body2,
          ),
          SizedBox(height: 16.h),
          
          // Cart items list
          ListView.separated(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: cart.items.length,
            separatorBuilder: (_, __) => SizedBox(height: 12.h),
            itemBuilder: (context, index) {
              return CartItemTile(item: cart.items[index]);
            },
          ),
          SizedBox(height: 24.h),
          
          // Promo code
          const PromoCodeSection(),
          SizedBox(height: 24.h),
          
          // Cart summary
          CartSummary(cart: cart),
          SizedBox(height: 100.h), // Space for bottom bar
        ],
      ),
    );
  }
}
```

### 4.2 Checkout Page

```dart
// lib/presentation/pages/checkout/checkout_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:smart_dairy_mobile/core/router/route_names.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/presentation/blocs/cart/cart_bloc.dart';
import 'package:smart_dairy_mobile/presentation/blocs/order/order_bloc.dart';
import 'checkout_address_section.dart';
import 'checkout_delivery_slot.dart';
import 'checkout_payment_section.dart';
import 'checkout_summary.dart';

class CheckoutPage extends StatefulWidget {
  const CheckoutPage({super.key});
  
  @override
  State<CheckoutPage> createState() => _CheckoutPageState();
}

class _CheckoutPageState extends State<CheckoutPage> {
  int _selectedAddressId = 1;
  int? _selectedDeliverySlot;
  String _selectedPaymentMethod = 'cod';
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      appBar: AppBar(
        title: const Text('Checkout'),
      ),
      body: BlocListener<OrderBloc, OrderState>(
        listener: (context, state) {
          state.whenOrNull(
            created: (order) {
              context.go(RouteNames.orderConfirmation, extra: order);
            },
            error: (message) {
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text(message),
                  backgroundColor: AppTheme.errorColor,
                ),
              );
            },
          );
        },
        child: SingleChildScrollView(
          padding: EdgeInsets.all(AppTheme.lg),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Delivery address
              CheckoutAddressSection(
                selectedAddressId: _selectedAddressId,
                onAddressSelected: (id) {
                  setState(() {
                    _selectedAddressId = id;
                  });
                },
              ),
              SizedBox(height: 24.h),
              
              // Delivery slot
              CheckoutDeliverySlot(
                selectedSlot: _selectedDeliverySlot,
                onSlotSelected: (slot) {
                  setState(() {
                    _selectedDeliverySlot = slot;
                  });
                },
              ),
              SizedBox(height: 24.h),
              
              // Payment method
              CheckoutPaymentSection(
                selectedMethod: _selectedPaymentMethod,
                onMethodSelected: (method) {
                  setState(() {
                    _selectedPaymentMethod = method;
                  });
                },
              ),
              SizedBox(height: 24.h),
              
              // Order summary
              BlocBuilder<CartBloc, CartState>(
                builder: (context, state) {
                  return state.maybeWhen(
                    loaded: (cart) => CheckoutSummary(cart: cart),
                    orElse: () => const SizedBox.shrink(),
                  );
                },
              ),
              SizedBox(height: 100.h),
            ],
          ),
        ),
      ),
      bottomNavigationBar: BlocBuilder<CartBloc, CartState>(
        builder: (context, cartState) {
          return cartState.maybeWhen(
            loaded: (cart) => Container(
              padding: EdgeInsets.all(AppTheme.lg),
              decoration: BoxDecoration(
                color: AppTheme.surface,
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.05),
                    blurRadius: 10,
                    offset: const Offset(0, -4),
                  ),
                ],
              ),
              child: SafeArea(
                child: BlocBuilder<OrderBloc, OrderState>(
                  builder: (context, orderState) {
                    final isLoading = orderState is OrderLoading;
                    
                    return SizedBox(
                      width: double.infinity,
                      height: 56.h,
                      child: ElevatedButton(
                        onPressed: isLoading || _selectedDeliverySlot == null
                            ? null
                            : () {
                                context.read<OrderBloc>().add(
                                  OrderEvent.orderCreated(
                                    deliveryAddressId: _selectedAddressId,
                                    deliverySlot: _selectedDeliverySlot!,
                                    paymentMethod: _selectedPaymentMethod,
                                  ),
                                );
                              },
                        child: isLoading
                            ? const CircularProgressIndicator(
                                color: Colors.white,
                              )
                            : Text(
                                'Place Order - ৳${cart.total.toStringAsFixed(2)}',
                              ),
                      ),
                    );
                  },
                ),
              ),
            ),
            orElse: () => const SizedBox.shrink(),
          );
        },
      ),
    );
  }
}
```

---

## 5. ORDER MANAGEMENT

### 5.1 Orders Page

```dart
// lib/presentation/pages/orders/orders_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/presentation/blocs/order/order_bloc.dart';
import 'order_list_tab.dart';

class OrdersPage extends StatefulWidget {
  const OrdersPage({super.key});
  
  @override
  State<OrdersPage> createState() => _OrdersPageState();
}

class _OrdersPageState extends State<OrdersPage>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  
  final List<String> _tabs = [
    'Active',
    'Delivered',
    'Cancelled',
  ];
  
  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: _tabs.length, vsync: this);
    context.read<OrderBloc>().add(const OrderEvent.ordersLoaded());
  }
  
  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      appBar: AppBar(
        title: const Text('My Orders'),
        bottom: TabBar(
          controller: _tabController,
          tabs: _tabs.map((tab) => Tab(text: tab)).toList(),
          labelColor: AppTheme.primaryColor,
          unselectedLabelColor: AppTheme.textSecondary,
          indicatorColor: AppTheme.primaryColor,
          labelStyle: AppTheme.subtitle1,
          unselectedLabelStyle: AppTheme.subtitle1.copyWith(
            color: AppTheme.textSecondary,
          ),
        ),
      ),
      body: TabBarView(
        controller: _tabController,
        children: [
          OrderListTab(
            statuses: ['pending', 'confirmed', 'processing', 'shipped'],
          ),
          OrderListTab(statuses: ['delivered']),
          OrderListTab(statuses: ['cancelled']),
        ],
      ),
    );
  }
}
```

### 5.2 Order Detail Page

```dart
// lib/presentation/pages/orders/order_detail_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/data/models/order/order_model.dart';
import 'order_status_timeline.dart';
import 'order_items_list.dart';
import 'order_action_buttons.dart';

class OrderDetailPage extends StatelessWidget {
  final OrderModel order;
  
  const OrderDetailPage({
    super.key,
    required this.order,
  });
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      appBar: AppBar(
        title: Text('Order ${order.name}'),
        actions: [
          IconButton(
            onPressed: () {
              // Share order
            },
            icon: const Icon(Icons.share_outlined),
          ),
          IconButton(
            onPressed: () {
              // Download invoice
            },
            icon: const Icon(Icons.receipt_outlined),
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: EdgeInsets.all(AppTheme.lg),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Order status header
            _buildStatusHeader(),
            SizedBox(height: 24.h),
            
            // Status timeline
            if (order.statusHistory.isNotEmpty) ...[
              OrderStatusTimeline(history: order.statusHistory),
              SizedBox(height: 24.h),
            ],
            
            // Delivery info
            _buildDeliveryInfo(),
            SizedBox(height: 24.h),
            
            // Order items
            OrderItemsList(items: order.items),
            SizedBox(height: 24.h),
            
            // Payment info
            _buildPaymentInfo(),
            SizedBox(height: 24.h),
            
            // Order summary
            _buildOrderSummary(),
            SizedBox(height: 100.h),
          ],
        ),
      ),
      bottomNavigationBar: order.isCancelable
          ? OrderActionButtons(order: order)
          : null,
    );
  }
  
  Widget _buildStatusHeader() {
    Color statusColor;
    IconData statusIcon;
    
    switch (order.status) {
      case 'pending':
        statusColor = AppTheme.warningColor;
        statusIcon = Icons.access_time;
        break;
      case 'confirmed':
        statusColor = AppTheme.primaryColor;
        statusIcon = Icons.check_circle_outline;
        break;
      case 'processing':
        statusColor = AppTheme.accentColor;
        statusIcon = Icons.sync;
        break;
      case 'shipped':
        statusColor = AppTheme.primaryLight;
        statusIcon = Icons.local_shipping;
        break;
      case 'delivered':
        statusColor = AppTheme.successColor;
        statusIcon = Icons.check_circle;
        break;
      case 'cancelled':
        statusColor = AppTheme.errorColor;
        statusIcon = Icons.cancel;
        break;
      default:
        statusColor = AppTheme.textTertiary;
        statusIcon = Icons.info;
    }
    
    return Container(
      padding: EdgeInsets.all(AppTheme.lg),
      decoration: BoxDecoration(
        color: statusColor.withOpacity(0.1),
        borderRadius: BorderRadius.circular(AppTheme.mediumRadius),
      ),
      child: Row(
        children: [
          Container(
            padding: EdgeInsets.all(12.w),
            decoration: BoxDecoration(
              color: statusColor.withOpacity(0.2),
              shape: BoxShape.circle,
            ),
            child: Icon(
              statusIcon,
              color: statusColor,
              size: 24.w,
            ),
          ),
          SizedBox(width: 16.w),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  order.displayStatus,
                  style: AppTheme.heading3.copyWith(color: statusColor),
                ),
                SizedBox(height: 4.h),
                Text(
                  'Ordered on ${_formatDate(order.orderDate)}',
                  style: AppTheme.body2,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
  
  Widget _buildDeliveryInfo() {
    return Card(
      child: Padding(
        padding: EdgeInsets.all(AppTheme.lg),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Delivery Information',
              style: AppTheme.subtitle1,
            ),
            SizedBox(height: 16.h),
            if (order.deliveryAddress != null) ...[
              _buildInfoRow(
                icon: Icons.location_on_outlined,
                title: 'Address',
                value: order.deliveryAddress!.formattedAddress,
              ),
              SizedBox(height: 12.h),
            ],
            _buildInfoRow(
              icon: Icons.calendar_today_outlined,
              title: 'Delivery Date',
              value: order.deliveryDate != null
                  ? _formatDate(order.deliveryDate!)
                  : 'To be confirmed',
            ),
            if (order.deliverySlot != null) ...[
              SizedBox(height: 12.h),
              _buildInfoRow(
                icon: Icons.access_time,
                title: 'Time Slot',
                value: order.deliverySlot!,
              ),
            ],
          ],
        ),
      ),
    );
  }
  
  Widget _buildPaymentInfo() {
    return Card(
      child: Padding(
        padding: EdgeInsets.all(AppTheme.lg),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Payment Information',
              style: AppTheme.subtitle1,
            ),
            SizedBox(height: 16.h),
            _buildInfoRow(
              icon: Icons.payment,
              title: 'Payment Method',
              value: _formatPaymentMethod(order.paymentMethod),
            ),
            SizedBox(height: 12.h),
            _buildInfoRow(
              icon: Icons.check_circle_outline,
              title: 'Payment Status',
              value: order.paymentStatus.toUpperCase(),
              valueColor: order.paymentStatus == 'captured'
                  ? AppTheme.successColor
                  : AppTheme.warningColor,
            ),
            if (order.transactionId != null) ...[
              SizedBox(height: 12.h),
              _buildInfoRow(
                icon: Icons.receipt,
                title: 'Transaction ID',
                value: order.transactionId!,
              ),
            ],
          ],
        ),
      ),
    );
  }
  
  Widget _buildOrderSummary() {
    return Card(
      child: Padding(
        padding: EdgeInsets.all(AppTheme.lg),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Order Summary',
              style: AppTheme.subtitle1,
            ),
            SizedBox(height: 16.h),
            _buildSummaryRow('Subtotal', order.subtotal),
            if (order.discount > 0) ...[
              SizedBox(height: 8.h),
              _buildSummaryRow('Discount', -order.discount, isDiscount: true),
            ],
            if (order.promotionDiscount != null && order.promotionDiscount! > 0) ...[
              SizedBox(height: 8.h),
              _buildSummaryRow(
                'Promo (${order.promotionCode})',
                -order.promotionDiscount!,
                isDiscount: true,
              ),
            ],
            SizedBox(height: 8.h),
            _buildSummaryRow('Delivery Fee', order.deliveryFee),
            if (order.tax > 0) ...[
              SizedBox(height: 8.h),
              _buildSummaryRow('Tax', order.tax),
            ],
            Divider(height: 24.h),
            _buildSummaryRow('Total', order.total, isTotal: true),
            if (order.loyaltyPointsEarned != null && order.loyaltyPointsEarned! > 0) ...[
              SizedBox(height: 8.h),
              Text(
                '+${order.loyaltyPointsEarned} loyalty points earned',
                style: AppTheme.caption.copyWith(color: AppTheme.accentColor),
              ),
            ],
          ],
        ),
      ),
    );
  }
  
  Widget _buildInfoRow({
    required IconData icon,
    required String title,
    required String value,
    Color? valueColor,
  }) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(
          icon,
          size: 20.w,
          color: AppTheme.textSecondary,
        ),
        SizedBox(width: 12.w),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title,
                style: AppTheme.caption,
              ),
              SizedBox(height: 2.h),
              Text(
                value,
                style: AppTheme.body1.copyWith(color: valueColor),
              ),
            ],
          ),
        ),
      ],
    );
  }
  
  Widget _buildSummaryRow(String label, double amount, {
    bool isDiscount = false,
    bool isTotal = false,
  }) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: isTotal ? AppTheme.subtitle1 : AppTheme.body1,
        ),
        Text(
          '${isDiscount ? '-' : ''}৳${amount.abs().toStringAsFixed(2)}',
          style: isTotal
              ? AppTheme.heading3.copyWith(color: AppTheme.primaryColor)
              : AppTheme.body1,
        ),
      ],
    );
  }
  
  String _formatDate(DateTime date) {
    return '${date.day}/${date.month}/${date.year}';
  }
  
  String _formatPaymentMethod(String method) {
    switch (method) {
      case 'cod':
        return 'Cash on Delivery';
      case 'bkash':
        return 'bKash';
      case 'nagad':
        return 'Nagad';
      case 'rocket':
        return 'Rocket';
      case 'card':
        return 'Credit/Debit Card';
      default:
        return method;
    }
  }
}
```

---

## 6. SUBSCRIPTION MANAGEMENT

### 6.1 Subscriptions Page

```dart
// lib/presentation/pages/subscriptions/subscriptions_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:smart_dairy_mobile/core/router/route_names.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/presentation/blocs/subscription/subscription_bloc.dart';
import 'subscription_card.dart';

class SubscriptionsPage extends StatelessWidget {
  const SubscriptionsPage({super.key});
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      appBar: AppBar(
        title: const Text('My Subscriptions'),
        actions: [
          TextButton.icon(
            onPressed: () {
              context.push(RouteNames.subscriptionCreate);
            },
            icon: const Icon(Icons.add),
            label: const Text('New'),
          ),
        ],
      ),
      body: BlocBuilder<SubscriptionBloc, SubscriptionState>(
        builder: (context, state) {
          return state.when(
            initial: () => const Center(child: CircularProgressIndicator()),
            loading: () => const Center(child: CircularProgressIndicator()),
            loaded: (subscriptions) => subscriptions.isEmpty
                ? _buildEmptyState(context)
                : _buildSubscriptionList(context, subscriptions),
            error: (message) => Center(child: Text('Error: $message')),
          );
        },
      ),
    );
  }
  
  Widget _buildEmptyState(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.repeat,
            size: 80.w,
            color: AppTheme.textTertiary,
          ),
          SizedBox(height: 24.h),
          Text(
            'No Active Subscriptions',
            style: AppTheme.heading3,
          ),
          SizedBox(height: 8.h),
          Text(
            'Subscribe to get fresh milk delivered daily!',
            style: AppTheme.body1.copyWith(color: AppTheme.textSecondary),
          ),
          SizedBox(height: 32.h),
          ElevatedButton.icon(
            onPressed: () {
              context.push(RouteNames.subscriptionCreate);
            },
            icon: const Icon(Icons.add),
            label: const Text('Create Subscription'),
          ),
        ],
      ),
    );
  }
  
  Widget _buildSubscriptionList(BuildContext context, List<SubscriptionModel> subscriptions) {
    return ListView.separated(
      padding: EdgeInsets.all(AppTheme.lg),
      itemCount: subscriptions.length,
      separatorBuilder: (_, __) => SizedBox(height: 16.h),
      itemBuilder: (context, index) {
        return SubscriptionCard(subscription: subscriptions[index]);
      },
    );
  }
}
```

### 6.2 Subscription Card

```dart
// lib/presentation/pages/subscriptions/subscription_card.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/data/models/subscription/subscription_model.dart';
import 'package:smart_dairy_mobile/presentation/blocs/subscription/subscription_bloc.dart';

class SubscriptionCard extends StatelessWidget {
  final SubscriptionModel subscription;
  
  const SubscriptionCard({
    super.key,
    required this.subscription,
  });
  
  Color get _statusColor {
    switch (subscription.status) {
      case 'active':
        return AppTheme.successColor;
      case 'paused':
        return AppTheme.warningColor;
      case 'cancelled':
        return AppTheme.errorColor;
      default:
        return AppTheme.textTertiary;
    }
  }
  
  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: EdgeInsets.all(AppTheme.lg),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Container(
                  padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 6.h),
                  decoration: BoxDecoration(
                    color: _statusColor.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(AppTheme.smallRadius),
                  ),
                  child: Text(
                    subscription.status.toUpperCase(),
                    style: AppTheme.caption.copyWith(
                      color: _statusColor,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ),
                PopupMenuButton<String>(
                  onSelected: (value) => _handleMenuAction(context, value),
                  itemBuilder: (context) => [
                    if (subscription.status == 'active')
                      const PopupMenuItem(
                        value: 'pause',
                        child: Text('Pause'),
                      ),
                    if (subscription.status == 'paused')
                      const PopupMenuItem(
                        value: 'resume',
                        child: Text('Resume'),
                      ),
                    const PopupMenuItem(
                      value: 'modify',
                      child: Text('Modify'),
                    ),
                    const PopupMenuItem(
                      value: 'history',
                      child: Text('View History'),
                    ),
                    if (subscription.status != 'cancelled')
                      PopupMenuItem(
                        value: 'cancel',
                        child: Text(
                          'Cancel',
                          style: TextStyle(color: AppTheme.errorColor),
                        ),
                      ),
                  ],
                ),
              ],
            ),
            SizedBox(height: 16.h),
            
            // Plan name
            Text(
              subscription.planName,
              style: AppTheme.subtitle1,
            ),
            SizedBox(height: 4.h),
            Text(
              subscription.frequencyDisplay,
              style: AppTheme.body2,
            ),
            SizedBox(height: 16.h),
            
            // Items
            ...subscription.items.map((item) => Padding(
              padding: EdgeInsets.only(bottom: 8.h),
              child: Row(
                children: [
                  Container(
                    width: 8.w,
                    height: 8.w,
                    decoration: BoxDecoration(
                      color: AppTheme.primaryColor,
                      shape: BoxShape.circle,
                    ),
                  ),
                  SizedBox(width: 12.w),
                  Expanded(
                    child: Text(
                      '${item.quantity}x ${item.productName}',
                      style: AppTheme.body1,
                    ),
                  ),
                  Text(
                    '৳${item.subtotal.toStringAsFixed(0)}',
                    style: AppTheme.subtitle1,
                  ),
                ],
              ),
            )),
            
            Divider(height: 24.h),
            
            // Footer info
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Next Delivery',
                      style: AppTheme.caption,
                    ),
                    SizedBox(height: 4.h),
                    Text(
                      _formatDate(subscription.nextDeliveryDate),
                      style: AppTheme.subtitle1,
                    ),
                  ],
                ),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.end,
                  children: [
                    Text(
                      'Monthly Cost',
                      style: AppTheme.caption,
                    ),
                    SizedBox(height: 4.h),
                    Text(
                      '৳${subscription.monthlyCost.toStringAsFixed(0)}',
                      style: AppTheme.price,
                    ),
                  ],
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
  
  void _handleMenuAction(BuildContext context, String action) {
    switch (action) {
      case 'pause':
        _showPauseDialog(context);
        break;
      case 'resume':
        context.read<SubscriptionBloc>().add(
          SubscriptionEvent.resumed(subscriptionId: subscription.id),
        );
        break;
      case 'cancel':
        _showCancelDialog(context);
        break;
      case 'modify':
        // Navigate to modify page
        break;
    }
  }
  
  void _showPauseDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Pause Subscription'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text('How long would you like to pause?'),
            SizedBox(height: 16.h),
            ...[1, 2, 4].map((weeks) => ListTile(
              title: Text('$weeks week${weeks > 1 ? 's' : ''}'),
              onTap: () {
                Navigator.pop(context);
                context.read<SubscriptionBloc>().add(
                  SubscriptionEvent.paused(
                    subscriptionId: subscription.id,
                    weeks: weeks,
                  ),
                );
              },
            )),
          ],
        ),
      ),
    );
  }
  
  void _showCancelDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Cancel Subscription?'),
        content: const Text(
          'Are you sure you want to cancel this subscription? This action cannot be undone.',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Keep Subscription'),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              context.read<SubscriptionBloc>().add(
                SubscriptionEvent.cancelled(subscriptionId: subscription.id),
              );
            },
            child: Text(
              'Cancel Subscription',
              style: TextStyle(color: AppTheme.errorColor),
            ),
          ),
        ],
      ),
    );
  }
  
  String _formatDate(DateTime date) {
    return '${date.day}/${date.month}/${date.year}';
  }
}
```

---

## 7. LOYALTY PROGRAM UI

### 7.1 Loyalty Dashboard

```dart
// lib/presentation/pages/loyalty/loyalty_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/presentation/blocs/loyalty/loyalty_bloc.dart';
import 'loyalty_tier_card.dart';
import 'loyalty_rewards_list.dart';
import 'loyalty_transactions_list.dart';

class LoyaltyPage extends StatelessWidget {
  const LoyaltyPage({super.key});
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      appBar: AppBar(
        title: const Text('Loyalty Rewards'),
        actions: [
          IconButton(
            onPressed: () {
              // Show info dialog
            },
            icon: const Icon(Icons.info_outline),
          ),
        ],
      ),
      body: BlocBuilder<LoyaltyBloc, LoyaltyState>(
        builder: (context, state) {
          return state.when(
            initial: () => const Center(child: CircularProgressIndicator()),
            loading: () => const Center(child: CircularProgressIndicator()),
            loaded: (account) => SingleChildScrollView(
              padding: EdgeInsets.all(AppTheme.lg),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Tier card
                  LoyaltyTierCard(account: account),
                  SizedBox(height: 24.h),
                  
                  // Available rewards
                  Text(
                    'Available Rewards',
                    style: AppTheme.heading3,
                  ),
                  SizedBox(height: 16.h),
                  const LoyaltyRewardsList(),
                  SizedBox(height: 24.h),
                  
                  // Recent transactions
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        'Recent Activity',
                        style: AppTheme.heading3,
                      ),
                      TextButton(
                        onPressed: () {
                          // View all transactions
                        },
                        child: const Text('View All'),
                      ),
                    ],
                  ),
                  SizedBox(height: 16.h),
                  LoyaltyTransactionsList(transactions: account.recentTransactions),
                ],
              ),
            ),
            error: (message) => Center(child: Text('Error: $message')),
          );
        },
      ),
    );
  }
}
```

### 7.2 Loyalty Tier Card

```dart
// lib/presentation/pages/loyalty/loyalty_tier_card.dart

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/data/models/loyalty/loyalty_account_model.dart';

class LoyaltyTierCard extends StatelessWidget {
  final LoyaltyAccountModel account;
  
  const LoyaltyTierCard({
    super.key,
    required this.account,
  });
  
  Color get _tierColor {
    switch (account.currentTier) {
      case 'bronze':
        return const Color(0xFFCD7F32);
      case 'silver':
        return const Color(0xFFC0C0C0);
      case 'gold':
        return const Color(0xFFFFD700);
      case 'platinum':
        return const Color(0xFFE5E4E2);
      default:
        return AppTheme.textTertiary;
    }
  }
  
  @override
  Widget build(BuildContext context) {
    final progress = account.totalPointsEarned / account.pointsForNextTier;
    
    return Container(
      padding: EdgeInsets.all(AppTheme.lg),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [
            _tierColor.withOpacity(0.8),
            _tierColor,
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(AppTheme.largeRadius),
        boxShadow: AppTheme.elevatedShadow,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Tier badge
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 8.h),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.2),
                  borderRadius: BorderRadius.circular(AppTheme.roundRadius),
                ),
                child: Row(
                  children: [
                    Icon(
                      Icons.stars,
                      color: Colors.white,
                      size: 16.w,
                    ),
                    SizedBox(width: 8.w),
                    Text(
                      account.currentTier.toUpperCase(),
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 12.sp,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ),
              if (account.nextTier != null)
                Text(
                  'Next: ${account.nextTier}',
                  style: TextStyle(
                    color: Colors.white.withOpacity(0.8),
                    fontSize: 12.sp,
                  ),
                ),
            ],
          ),
          SizedBox(height: 24.h),
          
          // Points
          Row(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text(
                account.availablePoints.toString(),
                style: TextStyle(
                  fontSize: 48.sp,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
              SizedBox(width: 8.w),
              Padding(
                padding: EdgeInsets.only(bottom: 8.h),
                child: Text(
                  'points',
                  style: TextStyle(
                    fontSize: 16.sp,
                    color: Colors.white.withOpacity(0.8),
                  ),
                ),
              ),
            ],
          ),
          SizedBox(height: 24.h),
          
          // Progress bar
          if (account.nextTier != null) ...[
            ClipRRect(
              borderRadius: BorderRadius.circular(AppTheme.smallRadius),
              child: LinearProgressIndicator(
                value: progress.clamp(0.0, 1.0),
                backgroundColor: Colors.white.withOpacity(0.2),
                valueColor: AlwaysStoppedAnimation<Color>(
                  Colors.white.withOpacity(0.9),
                ),
                minHeight: 8.h,
              ),
            ),
            SizedBox(height: 12.h),
            Text(
              '${account.pointsToNextTier} more points to reach ${account.nextTier}',
              style: TextStyle(
                fontSize: 12.sp,
                color: Colors.white.withOpacity(0.8),
              ),
            ),
          ],
        ],
      ),
    );
  }
}
```

---

## 8. PROFILE & SETTINGS

### 8.1 Profile Page

```dart
// lib/presentation/pages/profile/profile_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:smart_dairy_mobile/core/router/route_names.dart';
import 'package:smart_dairy_mobile/core/theme/app_theme.dart';
import 'package:smart_dairy_mobile/presentation/blocs/auth/auth_bloc.dart';
import 'profile_menu_item.dart';

class ProfilePage extends StatelessWidget {
  const ProfilePage({super.key});
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      body: BlocBuilder<AuthBloc, AuthState>(
        builder: (context, state) {
          return state.whenOrNull(
            authenticated: (user) => CustomScrollView(
              slivers: [
                // Header
                SliverToBoxAdapter(
                  child: _buildHeader(user),
                ),
                
                // Menu items
                SliverPadding(
                  padding: EdgeInsets.all(AppTheme.lg),
                  sliver: SliverList(
                    delegate: SliverChildListDelegate([
                      // Account section
                      Text(
                        'ACCOUNT',
                        style: AppTheme.caption.copyWith(
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      SizedBox(height: 12.h),
                      Card(
                        child: Column(
                          children: [
                            ProfileMenuItem(
                              icon: Icons.person_outline,
                              title: 'Edit Profile',
                              onTap: () => context.push(RouteNames.editProfile),
                            ),
                            const Divider(height: 1),
                            ProfileMenuItem(
                              icon: Icons.location_on_outlined,
                              title: 'My Addresses',
                              subtitle: '${user.addresses.length} saved',
                              onTap: () => context.push(RouteNames.addresses),
                            ),
                            const Divider(height: 1),
                            ProfileMenuItem(
                              icon: Icons.payment_outlined,
                              title: 'Payment Methods',
                              subtitle: '${user.paymentMethods.length} saved',
                              onTap: () => context.push(RouteNames.paymentMethods),
                            ),
                          ],
                        ),
                      ),
                      SizedBox(height: 24.h),
                      
                      // Preferences section
                      Text(
                        'PREFERENCES',
                        style: AppTheme.caption.copyWith(
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      SizedBox(height: 12.h),
                      Card(
                        child: Column(
                          children: [
                            ProfileMenuItem(
                              icon: Icons.notifications_outlined,
                              title: 'Notifications',
                              onTap: () => context.push(RouteNames.notifications),
                            ),
                            const Divider(height: 1),
                            ProfileMenuItem(
                              icon: Icons.language,
                              title: 'Language',
                              subtitle: 'English',
                              onTap: () => _showLanguageDialog(context),
                            ),
                            const Divider(height: 1),
                            ProfileMenuItem(
                              icon: Icons.dark_mode_outlined,
                              title: 'Dark Mode',
                              trailing: Switch(
                                value: false,
                                onChanged: (value) {
                                  // Toggle theme
                                },
                              ),
                            ),
                          ],
                        ),
                      ),
                      SizedBox(height: 24.h),
                      
                      // Support section
                      Text(
                        'SUPPORT',
                        style: AppTheme.caption.copyWith(
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      SizedBox(height: 12.h),
                      Card(
                        child: Column(
                          children: [
                            ProfileMenuItem(
                              icon: Icons.help_outline,
                              title: 'Help Center',
                              onTap: () => context.push(RouteNames.help),
                            ),
                            const Divider(height: 1),
                            ProfileMenuItem(
                              icon: Icons.chat_bubble_outline,
                              title: 'Contact Us',
                              onTap: () => context.push(RouteNames.contact),
                            ),
                            const Divider(height: 1),
                            ProfileMenuItem(
                              icon: Icons.description_outlined,
                              title: 'Terms & Privacy',
                              onTap: () => context.push(RouteNames.terms),
                            ),
                          ],
                        ),
                      ),
                      SizedBox(height: 24.h),
                      
                      // Logout
                      SizedBox(
                        width: double.infinity,
                        child: OutlinedButton.icon(
                          onPressed: () => _showLogoutDialog(context),
                          icon: const Icon(Icons.logout),
                          label: const Text('Logout'),
                          style: OutlinedButton.styleFrom(
                            foregroundColor: AppTheme.errorColor,
                            side: const BorderSide(color: AppTheme.errorColor),
                            padding: EdgeInsets.symmetric(vertical: 16.h),
                          ),
                        ),
                      ),
                      SizedBox(height: 32.h),
                      
                      // App version
                      Center(
                        child: Text(
                          'Version 1.0.0',
                          style: AppTheme.caption,
                        ),
                      ),
                      SizedBox(height: 16.h),
                    ]),
                  ),
                ),
              ],
            ),
          ) ?? const SizedBox.shrink();
        },
      ),
    );
  }
  
  Widget _buildHeader(UserModel user) {
    return Container(
      padding: EdgeInsets.all(AppTheme.lg),
      decoration: BoxDecoration(
        color: AppTheme.surface,
        boxShadow: AppTheme.cardShadow,
      ),
      child: SafeArea(
        child: Row(
          children: [
            // Avatar
            Container(
              width: 80.w,
              height: 80.w,
              decoration: BoxDecoration(
                color: AppTheme.primaryColor.withOpacity(0.1),
                shape: BoxShape.circle,
                image: user.avatar != null
                    ? DecorationImage(
                        image: NetworkImage(user.avatar!),
                        fit: BoxFit.cover,
                      )
                    : null,
              ),
              child: user.avatar == null
                  ? Icon(
                      Icons.person,
                      size: 40.w,
                      color: AppTheme.primaryColor,
                    )
                  : null,
            ),
            SizedBox(width: 16.w),
            
            // User info
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    user.name,
                    style: AppTheme.heading3,
                  ),
                  SizedBox(height: 4.h),
                  Text(
                    user.email ?? user.phone,
                    style: AppTheme.body2,
                  ),
                  SizedBox(height: 8.h),
                  Container(
                    padding: EdgeInsets.symmetric(
                      horizontal: 12.w,
                      vertical: 4.h,
                    ),
                    decoration: BoxDecoration(
                      color: AppTheme.accentColor.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(AppTheme.smallRadius),
                    ),
                    child: Text(
                      user.loyaltyTier.toUpperCase(),
                      style: TextStyle(
                        color: AppTheme.accentColor,
                        fontSize: 12.sp,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ),
                ],
              ),
            ),
            
            // Edit button
            IconButton(
              onPressed: () {
                // Navigate to edit profile
              },
              icon: const Icon(Icons.edit_outlined),
            ),
          ],
        ),
      ),
    );
  }
  
  void _showLanguageDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Select Language'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            ListTile(
              title: const Text('English'),
              trailing: const Icon(Icons.check, color: AppTheme.primaryColor),
              onTap: () => Navigator.pop(context),
            ),
            ListTile(
              title: const Text('বাংলা'),
              onTap: () => Navigator.pop(context),
            ),
          ],
        ),
      ),
    );
  }
  
  void _showLogoutDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Logout?'),
        content: const Text('Are you sure you want to logout?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              context.read<AuthBloc>().add(const AuthEvent.logoutRequested());
            },
            child: Text(
              'Logout',
              style: TextStyle(color: AppTheme.errorColor),
            ),
          ),
        ],
      ),
    );
  }
}
```

---

## 9. APPENDICES

### 9.1 Developer Tasks Matrix

| Day | Dev-Lead | Dev-1 | Dev-2 |
|-----|----------|-------|-------|
| 121 | UI design review | Theme setup | Color palette |
| 122 | Auth flow review | Login page | Register page |
| 123 | Product catalog review | Home page | Product card |
| 124 | Cart flow review | Cart page | Cart item tile |
| 125 | Checkout review | Checkout page | Payment section |
| 126 | Orders review | Orders list | Order detail |
| 127 | Subscription review | Subscriptions page | Subscription card |
| 128 | Loyalty review | Loyalty page | Rewards list |
| 129 | Profile review | Profile page | Settings |
| 130 | Final review | Bug fixes | Animation polish |

### 9.2 Screen List

| Screen | Route | Status |
|--------|-------|--------|
| Splash | /splash | Ready |
| Onboarding | /onboarding | Ready |
| Login | /login | Ready |
| Register | /register | Ready |
| Forgot Password | /forgot-password | Ready |
| Home | /home | Ready |
| Product Detail | /product/:id | Ready |
| Search | /search | Ready |
| Cart | /cart | Ready |
| Checkout | /checkout | Ready |
| Order Confirmation | /order-confirmation | Ready |
| Orders | /orders | Ready |
| Order Detail | /orders/:id | Ready |
| Subscriptions | /subscriptions | Ready |
| Subscription Create | /subscriptions/create | Ready |
| Loyalty | /loyalty | Ready |
| Profile | /profile | Ready |
| Edit Profile | /profile/edit | Ready |
| Addresses | /addresses | Ready |
| Payment Methods | /payment-methods | Ready |
| Notifications | /notifications | Ready |

### 9.3 Testing Checklist

- [ ] All screens render correctly
- [ ] Navigation flows work
- [ ] Form validation works
- [ ] Cart operations work
- [ ] Checkout flow completes
- [ ] Order tracking displays
- [ ] Subscription CRUD works
- [ ] Loyalty points display
- [ ] Profile updates save
- [ ] Dark mode toggle works
- [ ] Language switch works
- [ ] Logout clears data

---

*End of Milestone 3 - Customer Mobile App Development*

**Document Statistics:**
- **File Size**: 80+ KB
- **Code Examples**: 70+
- **UI Components**: 40+
- **Screens**: 20+
- **BLoC Examples**: 10+
- **State Management**: Complete

**Next Milestone**: Milestone 4 - Field Staff Mobile App

