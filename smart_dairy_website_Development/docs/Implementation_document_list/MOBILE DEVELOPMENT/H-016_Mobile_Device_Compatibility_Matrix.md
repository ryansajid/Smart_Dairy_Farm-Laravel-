# Mobile Device Compatibility Matrix

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | H-016 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Engineer |
| **Owner** | Mobile Lead |
| **Reviewer** | Product Manager |
| **Classification** | Internal |
| **Status** | Draft |

---

## Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-31 | QA Engineer | Initial version |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Minimum Requirements](#2-minimum-requirements)
3. [Android Device Matrix](#3-android-device-matrix)
4. [iOS Device Matrix](#4-ios-device-matrix)
5. [OS Version Support](#5-os-version-support)
6. [Screen Size Support](#6-screen-size-support)
7. [Hardware Requirements](#7-hardware-requirements)
8. [Network Requirements](#8-network-requirements)
9. [Test Device Lab](#9-test-device-lab)
10. [Cloud Testing](#10-cloud-testing)
11. [Compatibility Testing Checklist](#11-compatibility-testing-checklist)
12. [Issue Tracking](#12-issue-tracking)
13. [Update Strategy](#13-update-strategy)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the mobile device compatibility matrix for the Smart Dairy Mobile Application, ensuring comprehensive device coverage for the Bangladesh market while maintaining optimal user experience across all supported devices.

### 1.2 Scope

This matrix applies to:
- Smart Dairy Farmer Mobile App (Android & iOS)
- Smart Dairy Distributor Mobile App (Android & iOS)
- Smart Dairy Field Officer App (Android & iOS)
- Smart Dairy Consumer App (Android & iOS)

### 1.3 Compatibility Strategy

#### 1.3.1 Market-Driven Approach

| Priority | Market Segment | Coverage Target |
|----------|---------------|-----------------|
| P0 | Bangladesh primary market | 85% user coverage |
| P1 | Secondary markets (India, Nepal) | 10% user coverage |
| P2 | International expansion | 5% user coverage |

#### 1.3.2 Device Tier Strategy

```
Tier 1 (P0): Must support - Full feature parity, rigorous testing
Tier 2 (P1): Should support - Core features tested
Tier 3 (P2): Best effort - Basic functionality, community feedback
```

#### 1.3.3 Bangladesh Market Considerations

| Factor | Strategy |
|--------|----------|
| Device Diversity | Focus on top 20 models covering 80% market share |
| Price Sensitivity | Prioritize budget and mid-range devices |
| Network Conditions | Optimize for 2G/3G fallback |
| Screen Sizes | Target 6.0" - 6.7" (most popular range) |
| OS Fragmentation | Support Android 7.0+ (API 24+) |

---

## 2. Minimum Requirements

### 2.1 Minimum Hardware Specifications

| Component | Minimum Specification | Recommended Specification |
|-----------|----------------------|---------------------------|
| **Processor** | Quad-core 1.4 GHz | Octa-core 2.0 GHz+ |
| **RAM** | 2 GB | 4 GB+ |
| **Internal Storage** | 16 GB | 32 GB+ |
| **Available Storage** | 500 MB free | 1 GB+ free |
| **Screen Resolution** | 720 x 1280 (HD) | 1080 x 1920 (FHD) |
| **Screen Size** | 5.0 inches | 6.0 - 6.7 inches |
| **Battery** | 3000 mAh | 4000+ mAh |
| **Camera (Rear)** | 8 MP | 12+ MP |
| **Camera (Front)** | 5 MP | 8+ MP |

### 2.2 Minimum OS Versions

| Platform | Minimum Version | API Level |
|----------|----------------|-----------|
| Android | 7.0 (Nougat) | API 24 |
| iOS | 14.0 | N/A |

### 2.3 Network Requirements

| Network Type | Minimum | Recommended |
|--------------|---------|-------------|
| Mobile Data | 3G | 4G/LTE |
| WiFi | 802.11 b/g/n | 802.11 ac |
| Bluetooth | 4.0 | 5.0+ |

---

## 3. Android Device Matrix

### 3.1 Device Priority Classification

| Priority | Classification | Testing Frequency | Release Criteria |
|----------|---------------|-------------------|------------------|
| **P0** | Critical | Every release | Must pass all tests |
| **P1** | Important | Major releases | Must pass critical tests |
| **P2** | Nice-to-have | Quarterly | Best effort support |

### 3.2 Samsung Devices

#### 3.2.1 Galaxy A Series (Mid-Range - Bangladesh Popular)

| Model | Priority | Release Year | OS Range | Screen | RAM | Market Share |
|-------|----------|--------------|----------|--------|-----|--------------|
| Galaxy A15 5G | P0 | 2024 | Android 14 | 6.5" FHD+ | 6/8 GB | 8% |
| Galaxy A14 4G/5G | P0 | 2023 | Android 13 | 6.6" FHD+ | 4/6 GB | 12% |
| Galaxy A13 | P0 | 2022 | Android 12 | 6.6" FHD+ | 4/6 GB | 10% |
| Galaxy A05s | P0 | 2023 | Android 13 | 6.7" FHD+ | 4/6 GB | 6% |
| Galaxy A04s | P1 | 2022 | Android 12 | 6.5" HD+ | 3/4 GB | 5% |
| Galaxy A24 | P1 | 2023 | Android 13 | 6.5" FHD+ | 6/8 GB | 3% |
| Galaxy A34 5G | P1 | 2023 | Android 13 | 6.6" FHD+ | 6/8 GB | 4% |
| Galaxy A54 5G | P1 | 2023 | Android 13 | 6.4" FHD+ | 6/8 GB | 3% |

#### 3.2.2 Galaxy S Series (Premium)

| Model | Priority | Release Year | OS Range | Screen | RAM | Notes |
|-------|----------|--------------|----------|--------|-----|-------|
| Galaxy S24/S24+/Ultra | P1 | 2024 | Android 14 | 6.2-6.8" | 8-12 GB | Flagship reference |
| Galaxy S23/S23+/Ultra | P1 | 2023 | Android 13 | 6.1-6.8" | 8-12 GB | |
| Galaxy S22/S22+/Ultra | P2 | 2022 | Android 12 | 6.1-6.8" | 8-12 GB | |

#### 3.2.3 Galaxy M Series (Online Exclusive)

| Model | Priority | Release Year | OS Range | Screen | RAM | Notes |
|-------|----------|--------------|----------|--------|-----|-------|
| Galaxy M34 5G | P1 | 2023 | Android 13 | 6.5" FHD+ | 6/8 GB | Budget 5G |
| Galaxy M14 5G | P1 | 2023 | Android 13 | 6.6" FHD+ | 4/6 GB | Popular online |
| Galaxy M04 | P2 | 2023 | Android 12 | 6.5" HD+ | 4 GB | Entry-level |

### 3.3 Xiaomi Devices

#### 3.3.1 Redmi Series (Budget - Bangladesh Best Sellers)

| Model | Priority | Release Year | OS Range | Screen | RAM | Market Share |
|-------|----------|--------------|----------|--------|-----|--------------|
| Redmi 13C | P0 | 2023 | Android 13 | 6.74" HD+ | 4/6/8 GB | 15% |
| Redmi 12 | P0 | 2023 | Android 13 | 6.79" FHD+ | 4/8 GB | 10% |
| Redmi Note 13 | P0 | 2024 | Android 13 | 6.67" FHD+ | 6/8 GB | 8% |
| Redmi Note 12 | P0 | 2023 | Android 12 | 6.67" FHD+ | 4/6 GB | 12% |
| Redmi 12C | P0 | 2023 | Android 12 | 6.71" HD+ | 3/4/6 GB | 8% |
| Redmi A3 | P1 | 2024 | Android 14 | 6.71" HD+ | 3/4 GB | 4% |
| Redmi Note 11 | P1 | 2022 | Android 11 | 6.43" FHD+ | 4/6 GB | 5% |
| Redmi 10A | P1 | 2022 | Android 11 | 6.53" HD+ | 2/3 GB | 4% |
| Redmi 9A/9C | P2 | 2020 | Android 10 | 6.53" HD+ | 2/3 GB | Legacy support |

#### 3.3.2 POCO Series (Performance)

| Model | Priority | Release Year | OS Range | Screen | RAM | Notes |
|-------|----------|--------------|----------|--------|-----|-------|
| POCO M6 Pro | P1 | 2023 | Android 13 | 6.67" FHD+ | 8/12 GB | Gaming segment |
| POCO X6 Pro | P1 | 2024 | Android 14 | 6.67" FHD+ | 8/12 GB | Performance |
| POCO C65 | P1 | 2023 | Android 13 | 6.74" HD+ | 6/8 GB | Budget performance |

#### 3.3.3 Mi Series (Premium)

| Model | Priority | Release Year | OS Range | Screen | RAM | Notes |
|-------|----------|--------------|----------|--------|-----|-------|
| Xiaomi 14 | P2 | 2024 | Android 14 | 6.36" FHD+ | 8/12 GB | Premium tier |
| Xiaomi 13T | P2 | 2023 | Android 13 | 6.67" FHD+ | 8/12 GB | |

### 3.4 Realme / OPPO Devices

#### 3.4.1 Realme Series (Bangladesh Popular)

| Model | Priority | Release Year | OS Range | Screen | RAM | Market Share |
|-------|----------|--------------|----------|--------|-----|--------------|
| realme C67 | P0 | 2024 | Android 14 | 6.72" FHD+ | 6/8 GB | 6% |
| realme C53 | P0 | 2023 | Android 13 | 6.74" FHD+ | 4/6/8 GB | 8% |
| realme 11x 5G | P0 | 2023 | Android 13 | 6.72" FHD+ | 8 GB | 5% |
| realme C55 | P0 | 2023 | Android 13 | 6.72" FHD+ | 6/8 GB | 7% |
| realme Narzo 60x | P1 | 2023 | Android 13 | 6.72" FHD+ | 4/6 GB | 4% |
| realme 12 Pro+ | P1 | 2024 | Android 14 | 6.7" FHD+ | 8/12 GB | 3% |
| realme 11 Pro+ | P1 | 2023 | Android 13 | 6.7" FHD+ | 8/12 GB | 3% |
| realme C61 | P1 | 2024 | Android 14 | 6.745" HD+ | 4/6 GB | 4% |

#### 3.4.2 OPPO Series

| Model | Priority | Release Year | OS Range | Screen | RAM | Notes |
|-------|----------|--------------|----------|--------|-----|-------|
| OPPO A79 5G | P1 | 2023 | Android 13 | 6.72" FHD+ | 8 GB | |
| OPPO A58 | P1 | 2023 | Android 13 | 6.72" FHD+ | 6/8 GB | |
| OPPO A38 | P1 | 2023 | Android 13 | 6.56" HD+ | 4 GB | |
| OPPO A18 | P1 | 2023 | Android 13 | 6.56" HD+ | 4 GB | |

### 3.5 Vivo Devices

| Model | Priority | Release Year | OS Range | Screen | RAM | Market Share |
|-------|----------|--------------|----------|--------|-----|--------------|
| vivo Y27 5G | P0 | 2023 | Android 13 | 6.64" FHD+ | 6 GB | 5% |
| vivo Y36 | P0 | 2023 | Android 13 | 6.64" FHD+ | 8 GB | 6% |
| vivo Y22 | P1 | 2022 | Android 12 | 6.55" HD+ | 4/6 GB | 4% |
| vivo Y17s | P1 | 2023 | Android 13 | 6.56" HD+ | 6 GB | 3% |
| vivo V29 | P1 | 2023 | Android 13 | 6.78" FHD+ | 8/12 GB | Premium |
| vivo Y03 | P2 | 2024 | Android 14 | 6.56" HD+ | 4 GB | Entry-level |

### 3.6 Google Pixel Devices

| Model | Priority | Release Year | OS Range | Screen | RAM | Notes |
|-------|----------|--------------|----------|--------|-----|-------|
| Pixel 8/8 Pro | P1 | 2023 | Android 14 | 6.2-6.7" | 8-12 GB | Reference device |
| Pixel 7/7 Pro | P2 | 2022 | Android 13 | 6.3-6.7" | 8-12 GB | Reference device |
| Pixel 7a | P2 | 2023 | Android 13 | 6.1" FHD+ | 8 GB | Mid-range reference |

### 3.7 Other Bangladesh Popular Models

| Model | Priority | Release Year | OS Range | Screen | RAM | Notes |
|-------|----------|--------------|----------|--------|-----|-------|
| Infinix Hot 40 Pro | P1 | 2023 | Android 13 | 6.78" FHD+ | 8 GB | Popular budget |
| Infinix Note 30 | P1 | 2023 | Android 13 | 6.78" FHD+ | 8 GB | |
| Tecno Spark 20 Pro | P1 | 2024 | Android 14 | 6.78" FHD+ | 8 GB | Growing brand |
| Tecno Camon 20 | P1 | 2023 | Android 13 | 6.67" FHD+ | 8 GB | |
| Walton NEXG N9 | P1 | 2023 | Android 13 | 6.6" FHD+ | 8 GB | Local brand |
| Symphony Z60 Plus | P2 | 2023 | Android 13 | 6.6" HD+ | 4 GB | Local brand |
| Itel P55 | P2 | 2023 | Android 13 | 6.6" HD+ | 4 GB | Ultra-budget |

### 3.8 Android Device Summary Table

| Brand | P0 Devices | P1 Devices | P2 Devices | Coverage |
|-------|-----------|-----------|-----------|----------|
| Samsung | 4 | 5 | 3 | 48% |
| Xiaomi/Redmi/POCO | 5 | 4 | 1 | 52% |
| realme/OPPO | 4 | 5 | 0 | 30% |
| vivo | 2 | 4 | 1 | 21% |
| Google Pixel | 0 | 1 | 2 | 2% |
| Others | 0 | 5 | 2 | 12% |
| **Total** | **15** | **24** | **9** | **~85%** |

---

## 4. iOS Device Matrix

### 4.1 iPhone Devices

#### 4.1.1 Current Generation (P0 Priority)

| Model | Priority | Release Year | Min iOS | Screen | RAM | Market Share |
|-------|----------|--------------|---------|--------|-----|--------------|
| iPhone 15 Pro Max | P1 | 2023 | iOS 17 | 6.7" | 8 GB | 3% |
| iPhone 15 Pro | P1 | 2023 | iOS 17 | 6.1" | 8 GB | 2% |
| iPhone 15 Plus | P1 | 2023 | iOS 17 | 6.7" | 6 GB | 2% |
| iPhone 15 | P1 | 2023 | iOS 17 | 6.1" | 6 GB | 4% |

#### 4.1.2 iPhone 14 Series (P0 Priority)

| Model | Priority | Release Year | Min iOS | Screen | RAM | Market Share |
|-------|----------|--------------|---------|--------|-----|--------------|
| iPhone 14 Pro Max | P0 | 2022 | iOS 16 | 6.7" | 6 GB | 5% |
| iPhone 14 Pro | P0 | 2022 | iOS 16 | 6.1" | 6 GB | 4% |
| iPhone 14 Plus | P0 | 2022 | iOS 16 | 6.7" | 6 GB | 3% |
| iPhone 14 | P0 | 2022 | iOS 16 | 6.1" | 6 GB | 6% |

#### 4.1.3 iPhone 13 Series (P0 Priority)

| Model | Priority | Release Year | Min iOS | Screen | RAM | Market Share |
|-------|----------|--------------|---------|--------|-----|--------------|
| iPhone 13 Pro Max | P1 | 2021 | iOS 15 | 6.7" | 6 GB | 3% |
| iPhone 13 Pro | P1 | 2021 | iOS 15 | 6.1" | 6 GB | 2% |
| iPhone 13 | P0 | 2021 | iOS 15 | 6.1" | 4 GB | 8% |
| iPhone 13 mini | P2 | 2021 | iOS 15 | 5.4" | 4 GB | 1% |

#### 4.1.4 iPhone 12 Series (P1 Priority)

| Model | Priority | Release Year | Min iOS | Screen | RAM | Notes |
|-------|----------|--------------|---------|--------|-----|-------|
| iPhone 12 Pro Max | P2 | 2020 | iOS 14 | 6.7" | 6 GB | Legacy support |
| iPhone 12 Pro | P2 | 2020 | iOS 14 | 6.1" | 6 GB | Legacy support |
| iPhone 12 | P1 | 2020 | iOS 14 | 6.1" | 4 GB | Active support |
| iPhone 12 mini | P2 | 2020 | iOS 14 | 5.4" | 4 GB | Legacy support |

#### 4.1.5 iPhone SE Series (P1 Priority)

| Model | Priority | Release Year | Min iOS | Screen | RAM | Notes |
|-------|----------|--------------|---------|--------|-----|-------|
| iPhone SE (3rd Gen) | P1 | 2022 | iOS 15 | 4.7" | 4 GB | Small form factor |
| iPhone SE (2nd Gen) | P2 | 2020 | iOS 13 | 4.7" | 3 GB | Legacy support |

#### 4.1.6 Older iPhones (P2 Priority)

| Model | Priority | Release Year | Min iOS | Screen | RAM | Support Status |
|-------|----------|--------------|---------|--------|-----|----------------|
| iPhone 11 | P2 | 2019 | iOS 13 | 6.1" | 4 GB | Security fixes only |
| iPhone XR | P2 | 2018 | iOS 12 | 6.1" | 3 GB | Best effort |
| iPhone XS/XS Max | P2 | 2018 | iOS 12 | 5.8-6.5" | 4 GB | Best effort |

### 4.2 iPad Support

| Model | Priority | Support Status | Notes |
|-------|----------|----------------|-------|
| iPad Pro 12.9" (M2) | P2 | Not officially supported | Future consideration |
| iPad Pro 11" (M2) | P2 | Not officially supported | Future consideration |
| iPad Air (M1/M2) | P2 | Not officially supported | Future consideration |
| iPad (10th Gen) | P2 | Not officially supported | Future consideration |
| iPad mini (6th Gen) | P2 | Not officially supported | Future consideration |

**Note:** iPad support is planned for Phase 2 (Q3 2026) for Field Officer and Distributor apps only.

### 4.3 iOS Device Summary Table

| Generation | P0 Devices | P1 Devices | P2 Devices | Coverage |
|------------|-----------|-----------|-----------|----------|
| iPhone 15 | 0 | 4 | 0 | 11% |
| iPhone 14 | 4 | 0 | 0 | 18% |
| iPhone 13 | 1 | 2 | 1 | 14% |
| iPhone 12 | 1 | 1 | 2 | 8% |
| iPhone SE | 0 | 1 | 1 | 3% |
| Older | 0 | 0 | 4 | 6% |
| **Total** | **6** | **8** | **8** | **~12%** |

---

## 5. OS Version Support

### 5.1 Android OS Support Matrix

| Android Version | API Level | Support Status | Minimum App Version | Market Share (BD) |
|----------------|-----------|----------------|---------------------|-------------------|
| Android 15 | API 35 | Supported (Beta) | 2.0+ | <1% |
| Android 14 | API 34 | Fully Supported | 1.0+ | 8% |
| Android 13 | API 33 | Fully Supported | 1.0+ | 22% |
| Android 12 | API 31-32 | Fully Supported | 1.0+ | 18% |
| Android 11 | API 30 | Fully Supported | 1.0+ | 15% |
| Android 10 | API 29 | Supported | 1.0+ | 12% |
| Android 9 (Pie) | API 28 | Supported | 1.0+ | 10% |
| Android 8 (Oreo) | API 26-27 | Legacy Support | 1.0+ | 8% |
| Android 7 (Nougat) | API 24-25 | Minimum Support | 1.0+ | 6% |
| Android 6 and below | API <24 | Not Supported | N/A | 1% |

### 5.2 iOS Version Support Matrix

| iOS Version | Support Status | Minimum App Version | Market Share (BD) |
|-------------|---------------|---------------------|-------------------|
| iOS 18 | Supported (Beta) | 2.0+ | <1% |
| iOS 17.x | Fully Supported | 1.0+ | 25% |
| iOS 16.x | Fully Supported | 1.0+ | 35% |
| iOS 15.x | Supported | 1.0+ | 25% |
| iOS 14.x | Minimum Support | 1.0+ | 14% |
| iOS 13 and below | Not Supported | N/A | <1% |

### 5.3 OS Upgrade Strategy

| Action | Timeline | Criteria |
|--------|----------|----------|
| Add new OS support | Within 30 days of public release | Priority devices first |
| Deprecate old OS | Quarterly review | <5% market share |
| End support | 6 months notice | <2% market share |

---

## 6. Screen Size Support

### 6.1 Screen Size Categories

| Category | Diagonal Size | Width (dp) | Height (dp) | Priority |
|----------|--------------|------------|-------------|----------|
| Small | < 5.0" | 320-360 | 480-640 | P2 |
| Normal | 5.0" - 6.0" | 360-400 | 640-800 | P1 |
| Large | 6.0" - 6.5" | 400-420 | 800-900 | P0 |
| Extra Large | 6.5" - 7.0" | 420-480 | 900-1000 | P0 |
| Tablet | > 7.0" | 600+ | 900+ | P2 (Phase 2) |

### 6.2 Responsive Breakpoints

```
/* Mobile First Breakpoints */
sm: 360px   /* Small phones */
md: 400px   /* Medium phones */
lg: 420px   /* Large phones */
xl: 480px   /* Extra large phones */
2xl: 600px  /* Small tablets */
```

### 6.3 Screen Resolution Matrix

| Resolution | Aspect Ratio | DPI Range | Devices | Priority |
|------------|-------------|-----------|---------|----------|
| 720 x 1600 | 20:9 | 260-300 | Budget phones (Redmi A series) | P0 |
| 720 x 1280 | 16:9 | 280-320 | Older devices | P1 |
| 1080 x 1920 | 16:9 | 400-450 | Legacy premium | P2 |
| 1080 x 2400 | 20:9 | 380-420 | Mid-range (A series, Redmi) | P0 |
| 1080 x 2460 | 20.5:9 | 390-440 | Modern mid-range | P0 |
| 1170 x 2532 | 19.5:9 | 460 | iPhone 14/13 | P0 |
| 1284 x 2778 | 19.5:9 | 458 | iPhone 14 Plus/Pro Max | P0 |
| 1440 x 3200 | 20:9 | 500-550 | Premium (S series, Pixel) | P1 |

### 6.4 Screen Density Buckets

| Density Bucket | DPI Range | Qualifier | Primary Use |
|----------------|-----------|-----------|-------------|
| ldpi | 120-160 | - | Rarely used |
| mdpi | 160-240 | - | Baseline |
| hdpi | 240-320 | - | Older devices |
| xhdpi | 320-480 | - | Mid-range |
| xxhdpi | 480-640 | - | High-end |
| xxxhdpi | 640+ | - | Premium |

---

## 7. Hardware Requirements

### 7.1 Camera Requirements

#### 7.1.1 Minimum Camera Specifications

| Feature | Minimum | Recommended | Used For |
|---------|---------|-------------|----------|
| Rear Camera | 8 MP | 12+ MP | Document scanning, QR codes |
| Front Camera | 5 MP | 8+ MP | Farmer verification, selfie KYC |
| Auto Focus | Required | PDAF/Laser AF | Document clarity |
| Flash | Required | Dual-tone | Low light scanning |
| OIS | Optional | Recommended | Document stability |

#### 7.1.2 Camera Features by Use Case

| Use Case | Required Features | Quality Threshold |
|----------|------------------|-------------------|
| QR Code Scanning | Auto-focus, 5+ MP | 95% scan success rate |
| Document Upload | Auto-focus, 8+ MP | < 200ms capture time |
| Cattle Photo | 8+ MP, Flash | Clear identification |
| Farmer KYC | Front camera, 5+ MP | Face detection support |

### 7.2 NFC Support

| Feature | Status | Requirement Level | Notes |
|---------|--------|-------------------|-------|
| NFC Read | Supported | Optional | RFID tag reading |
| NFC Write | Supported | Optional | Tag programming |
| HCE (Host Card Emulation) | Planned | Phase 2 | Mobile payments |

**NFC Device Coverage:**
- Android: ~65% of supported devices have NFC
- iOS: 100% (iPhone 6 and later)

### 7.3 GPS Requirements

| Feature | Requirement | Accuracy | Notes |
|---------|-------------|----------|-------|
| GPS Location | Required | 10m accuracy | Farm location marking |
| A-GPS | Required | - | Faster fix times |
| GLONASS | Recommended | - | Better coverage |
| Galileo | Recommended | - | European satellites |
| BeiDou | Recommended | - | Asian coverage |

### 7.4 Biometric Sensors

| Feature | Status | Requirement Level | Used For |
|---------|--------|-------------------|----------|
| Fingerprint Scanner | Supported | Recommended | App authentication |
| Face Recognition | Supported | Optional | Quick unlock |
| Iris Scanner | Not Supported | N/A | - |

**Biometric Device Coverage:**
- Android: ~85% of P0/P1 devices have fingerprint
- iOS: 100% (Face ID or Touch ID)

### 7.5 Sensor Requirements

| Sensor | Status | Used For |
|--------|--------|----------|
| Accelerometer | Required | Screen rotation, shake detection |
| Gyroscope | Recommended | Camera stabilization |
| Proximity | Required | Screen dimming during calls |
| Light Sensor | Required | Auto brightness |
| Magnetometer | Recommended | Compass functionality |
| Barometer | Optional | Altitude (future feature) |

---

## 8. Network Requirements

### 8.1 Network Type Support

| Network | Support Level | Use Case | Priority |
|---------|--------------|----------|----------|
| 5G | Supported | High-speed sync, video upload | P1 |
| 4G/LTE | Fully Supported | Primary connectivity | P0 |
| 3G/HSPA | Supported | Fallback connectivity | P0 |
| 2G/EDGE | Limited Support | Emergency data only | P1 |
| WiFi | Fully Supported | Primary indoor connectivity | P0 |
| Bluetooth 5.0 | Supported | Peripheral connectivity | P1 |
| Bluetooth 4.x | Supported | Legacy peripheral support | P1 |

### 8.2 Network Performance Requirements

| Metric | Minimum | Recommended | Optimal |
|--------|---------|-------------|---------|
| Download Speed | 256 Kbps | 2 Mbps | 10+ Mbps |
| Upload Speed | 128 Kbps | 1 Mbps | 5+ Mbps |
| Latency | < 1000ms | < 200ms | < 50ms |
| Packet Loss | < 10% | < 2% | < 1% |
| Connection Stability | 80% uptime | 95% uptime | 99% uptime |

### 8.3 Bangladesh Network Conditions

| Operator | 4G Coverage | 3G Fallback | Notes |
|----------|-------------|-------------|-------|
| Grameenphone | 95% | 99% | Best coverage |
| Robi | 92% | 98% | Strong in rural |
| Banglalink | 88% | 96% | Urban focused |
| Teletalk | 75% | 90% | Government |

### 8.4 Network Optimization Strategy

| Scenario | Strategy |
|----------|----------|
| 2G/3G Network | Compress images, batch uploads, offline mode |
| Intermittent Connectivity | Queue operations, sync on reconnect |
| High Latency | Optimistic UI, background sync |
| Low Bandwidth | Progressive image loading, data saver mode |

---

## 9. Test Device Lab

### 9.1 Physical Device Inventory

#### 9.1.1 P0 Priority Devices (Must Have)

| Device | Quantity | Location | Primary Tester |
|--------|----------|----------|----------------|
| Samsung Galaxy A14 | 2 | Dhaka Office | QA Lead |
| Samsung Galaxy A15 5G | 1 | Dhaka Office | QA Engineer |
| Redmi 13C | 2 | Dhaka Office | QA Engineer |
| Redmi 12 | 2 | Dhaka Office | QA Engineer |
| Redmi Note 13 | 1 | Dhaka Office | QA Engineer |
| realme C53 | 2 | Dhaka Office | QA Engineer |
| realme C67 | 1 | Dhaka Office | QA Engineer |
| vivo Y27 5G | 1 | Dhaka Office | QA Engineer |
| vivo Y36 | 1 | Dhaka Office | QA Engineer |
| iPhone 14 | 2 | Dhaka Office | iOS QA |
| iPhone 13 | 1 | Dhaka Office | iOS QA |
| iPhone SE (3rd Gen) | 1 | Dhaka Office | iOS QA |

#### 9.1.2 P1 Priority Devices (Should Have)

| Device | Quantity | Location | Notes |
|--------|----------|----------|-------|
| Samsung Galaxy A34 | 1 | Remote | Loaned to field team |
| Samsung Galaxy M14 | 1 | Remote | Loaned to field team |
| Redmi Note 12 | 1 | Dhaka Office | |
| POCO M6 Pro | 1 | Dhaka Office | |
| OPPO A58 | 1 | Dhaka Office | |
| Infinix Hot 40 Pro | 1 | Dhaka Office | |
| iPhone 15 | 1 | Dhaka Office | Latest iOS |
| iPhone 12 | 1 | Remote | Regression testing |

#### 9.1.3 P2 Priority Devices (Reference)

| Device | Quantity | Source | Notes |
|--------|----------|--------|-------|
| Google Pixel 8 | 1 | Cloud | Reference for Android |
| Samsung Galaxy S24 | 1 | Cloud | Latest Android |
| iPhone 15 Pro | 1 | Cloud | Latest iOS |
| Low-end devices (Android 7-8) | 2 | Cloud | Minimum spec testing |

### 9.2 Device Allocation by Test Type

| Test Type | Device Count | Device Mix | Frequency |
|-----------|-------------|------------|-----------|
| Smoke Testing | 6 | 4 Android + 2 iOS | Every build |
| Regression Testing | 12 | 8 Android + 4 iOS | Every release |
| Compatibility Testing | 18 | 14 Android + 4 iOS | Major releases |
| Performance Testing | 8 | 6 Android + 2 iOS | Major releases |
| Field Testing | 6 | 4 Android + 2 iOS | Monthly |

### 9.3 Device Procurement Plan

| Quarter | New Devices | Budget (USD) | Priority |
|---------|-------------|--------------|----------|
| Q1 2026 | 6 | $1,800 | Replace aging devices |
| Q2 2026 | 4 | $1,200 | Add P1 coverage |
| Q3 2026 | 4 | $1,500 | New market models |
| Q4 2026 | 4 | $1,500 | Year-end refresh |

---

## 10. Cloud Testing

### 10.1 Firebase Test Lab Configuration

| Configuration | Value |
|---------------|-------|
| **Service** | Firebase Test Lab |
| **Account** | smartdairy-dev@smartdairy.com.bd |
| **Monthly Budget** | $500 USD |
| **Test Quota** | 100 physical device hours/month |

#### 10.1.1 Android Test Matrix

| Device Category | Devices | OS Versions | Orientation | Locale |
|-----------------|---------|-------------|-------------|--------|
| Physical - P0 | 10 | API 24, 28, 30, 33, 34 | Portrait, Landscape | en, bn |
| Physical - P1 | 8 | API 28, 30, 33 | Portrait | en |
| Virtual - Smoke | 5 | API 24, 28, 30, 33, 34 | Portrait | en |
| Virtual - Regression | 8 | API 24-34 | Portrait | en, bn |

#### 10.1.2 iOS Test Matrix (Firebase)

| Device | OS Version | Orientation | Locale |
|--------|------------|-------------|--------|
| iPhone 15 Pro | iOS 17.x | Portrait, Landscape | en |
| iPhone 14 | iOS 16.x, 17.x | Portrait | en, bn |
| iPhone SE 3rd Gen | iOS 16.x, 17.x | Portrait | en |
| iPhone 11 | iOS 15.x | Portrait | en |

### 10.2 AWS Device Farm Configuration

| Configuration | Value |
|---------------|-------|
| **Service** | AWS Device Farm |
| **Region** | ap-southeast-1 (Singapore) |
| **Monthly Budget** | $400 USD |
| **Device Minutes** | 10,000 minutes/month |

#### 10.2.1 Custom Device Pool - Bangladesh Market

| Device | Priority | Test Types |
|--------|----------|------------|
| Samsung Galaxy A14 | P0 | All tests |
| Redmi 13C | P0 | All tests |
| realme C53 | P0 | All tests |
| vivo Y27 | P0 | All tests |
| Infinix Note 30 | P1 | Compatibility |
| Tecno Spark 20 Pro | P1 | Compatibility |
| iPhone 14 | P0 | All tests |
| iPhone 13 | P0 | All tests |

### 10.3 BrowserStack/App Live Configuration

| Configuration | Value |
|---------------|-------|
| **Service** | BrowserStack App Live |
| **Plan** | Professional |
| **Monthly Cost** | $199 USD |
| **Parallel Sessions** | 5 |

**Use Cases:**
- Ad-hoc manual testing
- Quick device verification
- Customer issue reproduction
- Design review on multiple devices

### 10.4 Test Automation Integration

| Tool | Integration | Coverage |
|------|-------------|----------|
| Firebase Test Lab | CI/CD Pipeline | Smoke, Regression |
| AWS Device Farm | Weekly runs | Compatibility |
| BrowserStack | Manual testing | Exploratory |
| Appium + Grid | Local automation | P0 devices |

---

## 11. Compatibility Testing Checklist

### 11.1 Pre-Release Compatibility Checklist

#### 11.1.1 Installation & Launch

| # | Check | P0 Devices | P1 Devices | P2 Devices |
|---|-------|-----------|-----------|-----------|
| 1 | App installs successfully | ✓ | ✓ | Best effort |
| 2 | App launches within 3 seconds | ✓ | ✓ | Best effort |
| 3 | Splash screen displays correctly | ✓ | ✓ | Best effort |
| 4 | App doesn't crash on launch | ✓ | ✓ | Best effort |
| 5 | App handles low memory gracefully | ✓ | ✓ | - |

#### 11.1.2 UI/UX Compatibility

| # | Check | P0 Devices | P1 Devices | P2 Devices |
|---|-------|-----------|-----------|-----------|
| 1 | All screens render correctly | ✓ | ✓ | Best effort |
| 2 | No text truncation/clipping | ✓ | ✓ | Best effort |
| 3 | Touch targets minimum 48dp | ✓ | ✓ | ✓ |
| 4 | Images display without distortion | ✓ | ✓ | Best effort |
| 5 | Scroll performance > 60fps | ✓ | ✓ | - |
| 6 | Dark mode renders correctly | ✓ | ✓ | Best effort |
| 7 | Right-to-left (RTL) support | ✓ | Best effort | - |

#### 11.1.3 Feature Compatibility

| Feature | P0 Devices | P1 Devices | P2 Devices |
|---------|-----------|-----------|-----------|
| User Registration | ✓ | ✓ | Best effort |
| Login/Authentication | ✓ | ✓ | ✓ |
| QR Code Scanning | ✓ | ✓ | Best effort |
| Camera/Document Upload | ✓ | ✓ | Best effort |
| GPS Location | ✓ | ✓ | ✓ |
| Push Notifications | ✓ | ✓ | Best effort |
| Offline Mode | ✓ | ✓ | Best effort |
| Biometric Login | ✓ | Best effort | - |
| NFC (if available) | ✓ | Best effort | - |

#### 11.1.4 Performance Checklist

| # | Metric | P0 Target | P1 Target | P2 Target |
|---|--------|-----------|-----------|-----------|
| 1 | App launch time | < 3 sec | < 5 sec | < 8 sec |
| 2 | Screen transition | < 300ms | < 500ms | < 1 sec |
| 3 | API response | < 2 sec | < 3 sec | < 5 sec |
| 4 | Image load time | < 2 sec | < 3 sec | < 5 sec |
| 5 | Memory usage | < 150 MB | < 200 MB | < 300 MB |
| 6 | Battery drain | < 5%/hour | < 8%/hour | < 10%/hour |

### 11.2 Network Compatibility Checklist

| Network Condition | Test Scenario | Expected Behavior |
|-------------------|---------------|-------------------|
| 5G/4G | Full sync | All features work |
| 3G | Normal operation | Slower but functional |
| 2G | Fallback mode | Essential features only |
| Offline | No connectivity | Offline mode active |
| Intermittent | Flapping connection | Graceful degradation |
| High latency | > 500ms | Timeout handling |

### 11.3 OS Version Compatibility Matrix

| OS Version | Smoke | Regression | Performance | Security |
|------------|-------|------------|-------------|----------|
| Android 14 | ✓ | ✓ | ✓ | ✓ |
| Android 13 | ✓ | ✓ | ✓ | ✓ |
| Android 12 | ✓ | ✓ | ✓ | ✓ |
| Android 11 | ✓ | ✓ | - | ✓ |
| Android 10 | ✓ | ✓ | - | ✓ |
| Android 9 | ✓ | - | - | ✓ |
| Android 8 | ✓ | - | - | - |
| Android 7 | ✓ | - | - | - |
| iOS 17.x | ✓ | ✓ | ✓ | ✓ |
| iOS 16.x | ✓ | ✓ | ✓ | ✓ |
| iOS 15.x | ✓ | ✓ | - | ✓ |
| iOS 14.x | ✓ | - | - | ✓ |

---

## 12. Issue Tracking

### 12.1 Device-Specific Bug Classification

| Severity | Definition | Response Time | Example |
|----------|-----------|---------------|---------|
| Critical | App crash, data loss | 24 hours | Crash on Redmi 13C login |
| High | Feature broken | 48 hours | Camera not working on A14 |
| Medium | UI glitch | 1 week | Misaligned buttons on Y27 |
| Low | Cosmetic | Next release | Slight color variation |

### 12.2 Device-Specific Bug Template

```
**Device:** Samsung Galaxy A14
**OS:** Android 13
**App Version:** 1.2.3
**Priority:** P0

**Issue:** [Description]
**Steps to Reproduce:**
1. Step one
2. Step two
3. Step three

**Expected:** [Expected behavior]
**Actual:** [Actual behavior]
**Screenshots/Video:** [Attachments]
**Logs:** [Logcat/crash logs]
**Frequency:** [Always/Sometimes/Rarely]
**Workaround:** [If any]
```

### 12.3 Known Device-Specific Issues

| Issue ID | Device(s) | Issue | Status | Workaround |
|----------|-----------|-------|--------|------------|
| MOB-001 | Redmi 9A series | Camera focus slow | Open | Use manual focus |
| MOB-002 | Samsung A05s | GPS accuracy low | Fixed | Use WiFi assist |
| MOB-003 | realme C61 | Push notification delay | Monitoring | Check Doze mode |
| MOB-004 | iPhone SE | Screen layout compact | Accepted | Design compliant |

### 12.4 Issue Resolution SLA

| Priority | Critical | High | Medium | Low |
|----------|----------|------|--------|-----|
| P0 | 24h | 48h | 1 week | Next release |
| P1 | 48h | 1 week | 2 weeks | Next release |
| P2 | 1 week | 2 weeks | Best effort | Best effort |

---

## 13. Update Strategy

### 13.1 Adding New Device Support

| Phase | Activities | Timeline |
|-------|-----------|----------|
| 1. Research | Market analysis, device specs review | 1 week |
| 2. Procurement | Purchase/acquire test device | 1-2 weeks |
| 3. Testing | Compatibility testing | 1 week |
| 4. Documentation | Update matrix, release notes | 2 days |
| 5. Announcement | Update support page, notify users | 1 day |

### 13.2 Dropping Device Support

| Criteria | Threshold | Notice Period |
|----------|-----------|---------------|
| Market share | < 2% | 6 months |
| OS version | No longer receiving security updates | 6 months |
| Device age | > 5 years from launch | 6 months |
| Support cost | > 20% of dev effort for < 5% users | 3 months |

### 13.3 End-of-Support Schedule

| Device/OS | Current Status | End-of-Support Date | Replacement |
|-----------|---------------|---------------------|-------------|
| Android 7.x | Minimum support | June 30, 2026 | Android 8+ |
| iOS 14.x | Minimum support | December 31, 2026 | iOS 15+ |
| Redmi 9A/9C | P2 support | March 31, 2026 | Redmi 12C+ |
| iPhone XR | P2 support | June 30, 2026 | iPhone 12+ |

### 13.4 Quarterly Review Process

| Quarter | Activities | Output |
|---------|-----------|--------|
| Q1 | Market research, usage analytics | Device priority updates |
| Q2 | Mid-year review, budget planning | H2 procurement plan |
| Q3 | New device evaluation | Q4 device additions |
| Q4 | Annual review, next year planning | Updated matrix vNext |

### 13.5 Communication Plan

| Action | Communication | Audience | Timing |
|--------|--------------|----------|--------|
| New device added | Release notes, social media | All users | Immediate |
| Device support ending | Email, in-app notification | Affected users | 6 months prior |
| OS update required | In-app prompt, push | Outdated OS users | 3 months prior |
| Critical device issue | Hotfix release notes | All users | Immediate |

---

## 14. Appendices

### Appendix A: Complete Device List

#### A.1 Android Devices (Alphabetical)

| # | Brand | Model | Priority | OS Min | OS Max | Screen |
|---|-------|-------|----------|--------|--------|--------|
| 1 | Google | Pixel 7 | P2 | 13 | 15 | 6.3" |
| 2 | Google | Pixel 7 Pro | P2 | 13 | 15 | 6.7" |
| 3 | Google | Pixel 8 | P1 | 14 | 15 | 6.2" |
| 4 | Google | Pixel 8 Pro | P2 | 14 | 15 | 6.7" |
| 5 | Infinix | Hot 40 Pro | P1 | 13 | 14 | 6.78" |
| 6 | Infinix | Note 30 | P1 | 13 | 14 | 6.78" |
| 7 | Itel | P55 | P2 | 13 | 14 | 6.6" |
| 8 | OPPO | A18 | P1 | 13 | 14 | 6.56" |
| 9 | OPPO | A38 | P1 | 13 | 14 | 6.56" |
| 10 | OPPO | A58 | P1 | 13 | 14 | 6.72" |
| 11 | OPPO | A79 5G | P1 | 13 | 14 | 6.72" |
| 12 | POCO | C65 | P1 | 13 | 14 | 6.74" |
| 13 | POCO | M6 Pro | P1 | 13 | 14 | 6.67" |
| 14 | POCO | X6 Pro | P1 | 14 | 15 | 6.67" |
| 15 | realme | 11 Pro+ | P1 | 13 | 14 | 6.7" |
| 16 | realme | 11x 5G | P0 | 13 | 14 | 6.72" |
| 17 | realme | 12 Pro+ | P1 | 14 | 15 | 6.7" |
| 18 | realme | C53 | P0 | 13 | 14 | 6.74" |
| 19 | realme | C55 | P0 | 13 | 14 | 6.72" |
| 20 | realme | C61 | P1 | 14 | 15 | 6.745" |
| 21 | realme | C67 | P0 | 14 | 15 | 6.72" |
| 22 | realme | Narzo 60x | P1 | 13 | 14 | 6.72" |
| 23 | Redmi | 10A | P1 | 11 | 13 | 6.53" |
| 24 | Redmi | 12 | P0 | 13 | 15 | 6.79" |
| 25 | Redmi | 12C | P0 | 12 | 14 | 6.71" |
| 26 | Redmi | 13C | P0 | 13 | 15 | 6.74" |
| 27 | Redmi | 9A/9C | P2 | 10 | 12 | 6.53" |
| 28 | Redmi | A3 | P1 | 14 | 15 | 6.71" |
| 29 | Redmi | Note 11 | P1 | 11 | 13 | 6.43" |
| 30 | Redmi | Note 12 | P0 | 12 | 14 | 6.67" |
| 31 | Redmi | Note 13 | P0 | 13 | 15 | 6.67" |
| 32 | Samsung | Galaxy A04s | P1 | 12 | 14 | 6.5" |
| 33 | Samsung | Galaxy A05s | P0 | 13 | 15 | 6.7" |
| 34 | Samsung | Galaxy A13 | P0 | 12 | 14 | 6.6" |
| 35 | Samsung | Galaxy A14 | P0 | 13 | 15 | 6.6" |
| 36 | Samsung | Galaxy A15 5G | P0 | 14 | 15 | 6.5" |
| 37 | Samsung | Galaxy A24 | P1 | 13 | 15 | 6.5" |
| 38 | Samsung | Galaxy A34 5G | P1 | 13 | 15 | 6.6" |
| 39 | Samsung | Galaxy A54 5G | P1 | 13 | 15 | 6.4" |
| 40 | Samsung | Galaxy M04 | P2 | 12 | 14 | 6.5" |
| 41 | Samsung | Galaxy M14 5G | P1 | 13 | 15 | 6.6" |
| 42 | Samsung | Galaxy M34 5G | P1 | 13 | 15 | 6.5" |
| 43 | Samsung | Galaxy S22 series | P2 | 12 | 15 | 6.1-6.8" |
| 44 | Samsung | Galaxy S23 series | P1 | 13 | 15 | 6.1-6.8" |
| 45 | Samsung | Galaxy S24 series | P1 | 14 | 15 | 6.2-6.8" |
| 46 | Symphony | Z60 Plus | P2 | 13 | 14 | 6.6" |
| 47 | Tecno | Camon 20 | P1 | 13 | 14 | 6.67" |
| 48 | Tecno | Spark 20 Pro | P1 | 14 | 15 | 6.78" |
| 49 | vivo | V29 | P1 | 13 | 14 | 6.78" |
| 50 | vivo | Y03 | P2 | 14 | 15 | 6.56" |
| 51 | vivo | Y17s | P1 | 13 | 14 | 6.56" |
| 52 | vivo | Y22 | P1 | 12 | 14 | 6.55" |
| 53 | vivo | Y27 5G | P0 | 13 | 14 | 6.64" |
| 54 | vivo | Y36 | P0 | 13 | 14 | 6.64" |
| 55 | Walton | NEXG N9 | P1 | 13 | 14 | 6.6" |
| 56 | Xiaomi | 13T | P2 | 13 | 15 | 6.67" |
| 57 | Xiaomi | 14 | P2 | 14 | 15 | 6.36" |

#### A.2 iOS Devices

| # | Model | Priority | Min iOS | Screen | Launch Year |
|---|-------|----------|---------|--------|-------------|
| 1 | iPhone 11 | P2 | 13 | 6.1" | 2019 |
| 2 | iPhone 12 | P1 | 14 | 6.1" | 2020 |
| 3 | iPhone 12 mini | P2 | 14 | 5.4" | 2020 |
| 4 | iPhone 12 Pro | P2 | 14 | 6.1" | 2020 |
| 5 | iPhone 12 Pro Max | P2 | 14 | 6.7" | 2020 |
| 6 | iPhone 13 | P0 | 15 | 6.1" | 2021 |
| 7 | iPhone 13 mini | P2 | 15 | 5.4" | 2021 |
| 8 | iPhone 13 Pro | P1 | 15 | 6.1" | 2021 |
| 9 | iPhone 13 Pro Max | P1 | 15 | 6.7" | 2021 |
| 10 | iPhone 14 | P0 | 16 | 6.1" | 2022 |
| 11 | iPhone 14 Plus | P0 | 16 | 6.7" | 2022 |
| 12 | iPhone 14 Pro | P0 | 16 | 6.1" | 2022 |
| 13 | iPhone 14 Pro Max | P0 | 16 | 6.7" | 2022 |
| 14 | iPhone 15 | P1 | 17 | 6.1" | 2023 |
| 15 | iPhone 15 Plus | P1 | 17 | 6.7" | 2023 |
| 16 | iPhone 15 Pro | P1 | 17 | 6.1" | 2023 |
| 17 | iPhone 15 Pro Max | P1 | 17 | 6.7" | 2023 |
| 18 | iPhone SE (2nd Gen) | P2 | 13 | 4.7" | 2020 |
| 19 | iPhone SE (3rd Gen) | P1 | 15 | 4.7" | 2022 |
| 20 | iPhone XR | P2 | 12 | 6.1" | 2018 |
| 21 | iPhone XS | P2 | 12 | 5.8" | 2018 |
| 22 | iPhone XS Max | P2 | 12 | 6.5" | 2018 |

### Appendix B: Testing Procedures

#### B.1 Device Setup Procedure

```
1. Device Preparation
   - Factory reset device
   - Update to latest supported OS
   - Install necessary test tools
   - Configure test accounts

2. App Installation
   - Download from appropriate store (Play/App Store)
   - Verify signature/certificate
   - Document installation time
   - Check storage usage

3. Baseline Testing
   - Capture device information
   - Record baseline performance metrics
   - Take reference screenshots
   - Verify network connectivity
```

#### B.2 Compatibility Test Execution

```
Phase 1: Installation & Launch (15 min)
- Install from store
- First launch experience
- Cold start timing
- Permission handling

Phase 2: Core Flow (30 min)
- User registration/login
- Main navigation
- Critical user journeys
- Error scenarios

Phase 3: Feature Testing (45 min)
- Camera functionality
- GPS/location services
- Push notifications
- Background sync

Phase 4: Performance (30 min)
- Memory usage
- Battery consumption
- Network performance
- UI responsiveness

Phase 5: Edge Cases (20 min)
- Low battery
- Poor network
- Interrupted operations
- Background/foreground
```

#### B.3 Test Result Documentation

| Field | Description |
|-------|-------------|
| Test Date | Date of test execution |
| Tester | Name of QA engineer |
| Device | Device model and variant |
| OS Version | Exact OS version tested |
| App Version | Build number and version |
| Test Results | Pass/Fail/Partial |
| Issues Found | List of issues with IDs |
| Screenshots | Visual evidence |
| Performance Data | Metrics captured |
| Notes | Additional observations |

### Appendix C: Device Priority Matrix

| Priority | Device Count | Test Coverage | Release Gate |
|----------|-------------|---------------|--------------|
| P0 | 15 devices | 100% | Required |
| P1 | 24 devices | 80% | Recommended |
| P2 | 9+ devices | 50% | Optional |

### Appendix D: Bangladesh Market Data

#### D.1 Mobile Market Share (2025 Estimates)

| Brand | Market Share | Primary Segment |
|-------|-------------|-----------------|
| Xiaomi/Redmi | 28% | Budget/Mid-range |
| Samsung | 24% | All segments |
| realme | 12% | Budget/Mid-range |
| vivo | 10% | Budget/Mid-range |
| OPPO | 6% | Mid-range |
| Apple | 5% | Premium |
| Others | 15% | Various |

#### D.2 Popular Screen Sizes in Bangladesh

| Screen Size | Market Share | Priority |
|-------------|-------------|----------|
| 6.5" - 6.7" | 45% | P0 |
| 6.0" - 6.5" | 30% | P0 |
| 5.5" - 6.0" | 15% | P1 |
| < 5.5" | 5% | P2 |
| > 6.7" | 5% | P1 |

#### D.3 Network Distribution

| Network Type | Urban | Rural | Overall |
|--------------|-------|-------|---------|
| 4G/LTE | 85% | 65% | 75% |
| 3G | 10% | 25% | 17% |
| 2G | 5% | 10% | 8% |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | QA Engineer | _____________ | _______ |
| Owner | Mobile Lead | _____________ | _______ |
| Reviewer | Product Manager | _____________ | _______ |
| Approver | CTO | _____________ | _______ |

---

## Distribution List

| Role | Name | Department |
|------|------|------------|
| Mobile Development Lead | [Name] | Engineering |
| QA Lead | [Name] | Quality Assurance |
| Product Manager | [Name] | Product |
| DevOps Lead | [Name] | Engineering |
| Support Lead | [Name] | Customer Success |

---

*Document ID: H-016 | Version: 1.0 | Page 1 of 1*

*This document is confidential and proprietary to Smart Dairy Ltd.*
