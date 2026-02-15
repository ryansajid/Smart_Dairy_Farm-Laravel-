# H-014: Mobile Performance Optimization

## Smart Dairy Ltd. - Smart Dairy Web Portal System

---

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | H-014 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Tech Lead |
| **Status** | Approved |
| **Classification** | Implementation Document |

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Mobile Lead | Initial version |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Build Optimization](#2-build-optimization)
3. [App Size Optimization](#3-app-size-optimization)
4. [Rendering Performance](#4-rendering-performance)
5. [Memory Management](#5-memory-management)
6. [Image Optimization](#6-image-optimization)
7. [Network Optimization](#7-network-optimization)
8. [Database Performance](#8-database-performance)
9. [Startup Time](#9-startup-time)
10. [Battery Optimization](#10-battery-optimization)
11. [Profiling Tools](#11-profiling-tools)
12. [Performance Monitoring](#12-performance-monitoring)
13. [Benchmarking](#13-benchmarking)
14. [Best Practices](#14-best-practices)
15. [Platform-Specific Tips](#15-platform-specific-tips)
16. [Appendices](#16-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines comprehensive performance optimization strategies for the Smart Dairy mobile application, ensuring optimal user experience across all supported devices.

### 1.2 Scope

This document applies to:
- Flutter mobile application (Android & iOS)
- All application modules and features
- Release and debug builds
- All supported device tiers

### 1.3 Performance Goals

| Metric | Target | Critical Threshold |
|--------|--------|-------------------|
| **App Size** | < 50MB | < 80MB |
| **Cold Startup Time** | < 3 seconds | < 5 seconds |
| **Warm Startup Time** | < 1.5 seconds | < 2.5 seconds |
| **Frame Rate** | 60fps (16.67ms/frame) | > 55fps |
| **Memory Usage** | < 200MB | < 300MB |
| **Battery Impact** | Minimal background drain | < 5% per hour |
| **Network Request Time** | < 500ms (API) | < 2s |
| **Image Load Time** | < 200ms | < 500ms |

### 1.4 Key Performance Metrics

```dart
// Performance metrics tracking class
class PerformanceMetrics {
  static const int targetFrameTimeMs = 16; // 60fps = 16.67ms
  static const int maxMemoryMB = 200;
  static const int maxStartupTimeMs = 3000;
  static const int maxAppSizeMB = 50;
  
  // Current session metrics
  final Map<String, dynamic> _metrics = {};
  
  void recordMetric(String name, dynamic value) {
    _metrics[name] = {
      'value': value,
      'timestamp': DateTime.now().toIso8601String(),
    };
  }
  
  Map<String, dynamic> getMetrics() => Map.unmodifiable(_metrics);
}
```

### 1.5 Performance Budget

```yaml
# performance_budget.yaml
performance_budget:
  app_size:
    android_apk: 50MB
    android_aab: 45MB
    ios_ipa: 55MB
    
  startup_time:
    cold_start: 3000ms
    warm_start: 1500ms
    first_frame: 1000ms
    
  runtime:
    frame_time: 16.67ms
    memory_peak: 200MB
    memory_average: 150MB
    
  network:
    api_response: 500ms
    image_load: 200ms
    cache_hit_rate: 0.85
```

---

## 2. Build Optimization

### 2.1 Tree Shaking

Tree shaking eliminates unused code from the final bundle, significantly reducing app size.

```dart
// ✅ Good: Only imported functions are included
import 'package:smart_dairy/utils/validators.dart' show validateEmail;

// ❌ Bad: Imports entire library even if unused
import 'package:smart_dairy/utils/validators.dart';

// ✅ Good: Use deferred loading for large libraries
deferred import 'package:charts_flutter/flutter.dart' as charts;

Future<void> loadCharts() async {
  await charts.loadLibrary();
  // Use charts library
}
```

#### Build Configuration

```bash
# Enable tree shaking in release builds
flutter build apk --release --tree-shake-icons
flutter build ios --release

# Verify tree shaking with
flutter build apk --analyze-size
```

#### pubspec.yaml Configuration

```yaml
flutter:
  # Remove unused material design icons
  uses-material-design: true
  
  # Only include necessary font files
  fonts:
    - family: SmartDairyIcons
      fonts:
        - asset: fonts/SmartDairyIcons-Regular.ttf
          
  # Optimize asset delivery
  assets:
    - assets/images/ # Only include required images
    - assets/config/
    
# Enable code obfuscation
flutter_build:
  obfuscate: true
  split-debug-info: build/symbols/
```

### 2.2 Code Obfuscation

```bash
# Build with obfuscation and split debug info
flutter build apk --obfuscate --split-debug-info=./symbols/
flutter build appbundle --obfuscate --split-debug-info=./symbols/
flutter build ios --obfuscate --split-debug-info=./symbols/

# Symbolize stack traces
flutter symbolize -i stack_trace.txt -d symbols/app.android-arm64.symbols
```

### 2.3 Dart Compilation Optimization

```dart
// analysis_options.yaml
analyzer:
  strong-mode:
    implicit-casts: false
    implicit-dynamic: false
  
linter:
  rules:
    # Performance-related lints
    - prefer_const_constructors
    - prefer_const_literals_to_create_immutables
    - avoid_unnecessary_containers
    - avoid_print
    - prefer_final_locals
```

---

## 3. App Size Optimization

### 3.1 Resource Optimization

#### Image Compression

```yaml
# pubspec.yaml - Use optimized assets
flutter:
  assets:
    # Use WebP for better compression (Android)
    - assets/images/logo.webp
    # Use PNG for iOS with transparency needs
    - assets/images/icon.png
    # Use JPEG for photos
    - assets/images/banners/
```

```dart
// Asset optimization helper
class AssetOptimizer {
  static String getOptimizedPath(String basePath, {required TargetPlatform platform}) {
    switch (platform) {
      case TargetPlatform.android:
        // Use WebP on Android (API 14+)
        return basePath.replaceAll('.png', '.webp');
      case TargetPlatform.iOS:
        // Use compressed PNG on iOS
        return basePath;
      default:
        return basePath;
    }
  }
  
  // Select appropriate image resolution based on device pixel ratio
  static String getResolutionAwarePath(String path, double pixelRatio) {
    if (pixelRatio >= 3.0) {
      return path.replaceFirst('.', '@3x.');
    } else if (pixelRatio >= 2.0) {
      return path.replaceFirst('.', '@2x.');
    }
    return path;
  }
}
```

#### Font Subsetting

```bash
# Use font subsetting to reduce font file sizes
# Only include characters used in the app
pyftsubset fonts/NotoSans-Regular.ttf \
  --text="Smart DairyABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789" \
  --output-file=fonts/NotoSans-Subset.ttf
```

### 3.2 Code Splitting

```dart
// Feature-based code splitting
// lib/features/milk_production/milk_production_module.dart

class MilkProductionModule {
  static Future<void> initialize() async {
    // Lazy load dependencies
    await GetIt.instance.isReady<MilkProductionRepository>();
  }
  
  static Map<String, WidgetBuilder> get routes => {
    '/milk-production': (context) => const MilkProductionPage(),
    '/milk-production/history': (context) => const MilkHistoryPage(),
  };
}

// Main app with deferred loading
class SmartDairyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      routes: {
        '/': (context) => const HomePage(),
        // Routes loaded on demand
        '/milk-production': (context) => FutureBuilder(
          future: MilkProductionModule.initialize(),
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.done) {
              return const MilkProductionPage();
            }
            return const LoadingPage();
          },
        ),
      },
    );
  }
}
```

### 3.3 Dynamic Delivery (Android)

```gradle
// android/app/build.gradle
android {
    bundle {
        language {
            enableSplit = true
        }
        density {
            enableSplit = true
        }
        abi {
            enableSplit = true
        }
    }
}

// Dynamic feature module
android {
    dynamicFeatures = [':features:advanced_analytics']
}
```

```dart
// Play Core library for dynamic delivery
import 'package:play_core/play_core.dart';

class DynamicModuleManager {
  final SplitInstallManager _manager;
  
  DynamicModuleManager() : _manager = SplitInstallManagerFactory.create();
  
  Future<void> requestAnalyticsModule() async {
    const moduleName = 'advanced_analytics';
    
    try {
      final info = await _manager.getSessionStates();
      if (info.any((state) => 
          state.moduleNames.contains(moduleName) && 
          state.status == SplitInstallSessionStatus.installed)) {
        return; // Already installed
      }
      
      await _manager.startInstall(
        SplitInstallRequest.newBuilder()
          .addModule(moduleName)
          .build(),
      );
    } catch (e) {
      debugPrint('Failed to install module: $e');
    }
  }
}
```

### 3.4 App Size Analysis

```bash
# Analyze APK size
flutter build apk --analyze-size --target-platform android-arm64

# Analyze App Bundle size
flutter build appbundle --analyze-size

# Generate size report
flutter build apk --release --split-per-abi --analyze-size
```

```dart
// Size analysis widget for debugging
class SizeAnalysisPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Size Analysis')),
      body: FutureBuilder<Map<String, int>>(
        future: _analyzeAssetSizes(),
        builder: (context, snapshot) {
          if (!snapshot.hasData) {
            return const CircularProgressIndicator();
          }
          
          final sizes = snapshot.data!;
          return ListView.builder(
            itemCount: sizes.length,
            itemBuilder: (context, index) {
              final entry = sizes.entries.elementAt(index);
              return ListTile(
                title: Text(entry.key),
                subtitle: Text('${_formatBytes(entry.value)}'),
              );
            },
          );
        },
      ),
    );
  }
  
  String _formatBytes(int bytes) {
    if (bytes < 1024) return '$bytes B';
    if (bytes < 1024 * 1024) return '${(bytes / 1024).toStringAsFixed(2)} KB';
    return '${(bytes / (1024 * 1024)).toStringAsFixed(2)} MB';
  }
}
```

---

## 4. Rendering Performance

### 4.1 Widget Rebuild Optimization

#### const Constructors

```dart
// ✅ Good: Use const constructors
class OptimizedListItem extends StatelessWidget {
  final String title;
  final String subtitle;
  
  const OptimizedListItem({
    super.key,
    required this.title,
    required this.subtitle,
  });
  
  @override
  Widget build(BuildContext context) {
    return const ListTile(
      // These are compile-time constants
      leading: Icon(Icons.milk, color: Colors.blue),
      title: Text('Milk Production'),
      subtitle: Text('Daily Records'),
    );
  }
}

// ✅ Good: Extract widgets to avoid rebuilds
class MilkProductionList extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      itemCount: 100,
      itemBuilder: (context, index) {
        // Only this item rebuilds when data changes
        return MilkProductionItem(index: index);
      },
    );
  }
}

class MilkProductionItem extends StatelessWidget {
  final int index;
  
  const MilkProductionItem({super.key, required this.index});
  
  @override
  Widget build(BuildContext context) {
    return Consumer<MilkProductionProvider>(
      builder: (context, provider, child) {
        final record = provider.getRecord(index);
        return ListTile(
          title: Text(record.cowId),
          subtitle: Text('${record.volume}L'),
        );
      },
    );
  }
}
```

#### const Literals

```dart
// ✅ Good: Use const for literals
class StaticWidgets {
  static const EdgeInsets padding16 = EdgeInsets.all(16);
  static const EdgeInsets padding8 = EdgeInsets.all(8);
  static const BorderRadius borderRadius8 = BorderRadius.all(Radius.circular(8));
  static const TextStyle titleStyle = TextStyle(
    fontSize: 18,
    fontWeight: FontWeight.bold,
  );
}

// Usage
Container(
  padding: StaticWidgets.padding16,
  decoration: const BoxDecoration(
    borderRadius: StaticWidgets.borderRadius8,
  ),
  child: const Text('Title', style: StaticWidgets.titleStyle),
)
```

### 4.2 ListView Optimization

```dart
// ✅ Good: Use builder constructor for large lists
class OptimizedCowList extends StatelessWidget {
  final List<Cow> cows;
  
  const OptimizedCowList({super.key, required this.cows});
  
  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      // Key for efficient updates
      key: const PageStorageKey('cow_list'),
      // Item count
      itemCount: cows.length,
      // Add item extent for performance
      itemExtent: 72,
      // Cache items outside viewport
      cacheExtent: 200,
      // Build only visible items
      itemBuilder: (context, index) {
        return CowListItem(
          cow: cows[index],
          // Use ValueKey for efficient reconciliation
          key: ValueKey(cows[index].id),
        );
      },
    );
  }
}

// ✅ Good: Separate concerns
class CowListItem extends StatelessWidget {
  final Cow cow;
  
  const CowListItem({super.key, required this.cow});
  
  @override
  Widget build(BuildContext context) {
    // Minimal build method
    return ListTile(
      leading: const CowAvatar(),
      title: Text(cow.name),
      subtitle: Text(cow.breed),
      trailing: const Icon(Icons.chevron_right),
    );
  }
}

// ✅ Good: Use RepaintBoundary for complex widgets
class ComplexChart extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return RepaintBoundary(
      child: CustomPaint(
        size: const Size(300, 200),
        painter: MilkProductionChartPainter(),
      ),
    );
  }
}
```

### 4.3 State Management Optimization

```dart
// ✅ Good: Selective rebuilding with Provider
class CowSelector extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    // Only rebuild when selectedCowId changes
    return Selector<CowProvider, String>(
      selector: (context, provider) => provider.selectedCowId,
      builder: (context, selectedId, child) {
        return Text('Selected: $selectedId');
      },
    );
  }
}

// ✅ Good: Use child parameter to cache static parts
class OptimizedCard extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Consumer<DynamicDataProvider>(
      // This child is cached and not rebuilt
      child: const Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Icon(Icons.analytics),
            Text('Static Title'),
          ],
        ),
      ),
      builder: (context, provider, child) {
        return Card(
          child: Column(
            children: [
              // Static content (cached)
              child!,
              // Dynamic content
              Text('Value: ${provider.value}'),
            ],
          ),
        );
      },
    );
  }
}

// ✅ Good: Use memoization for expensive computations
class MilkStatsWidget extends StatelessWidget {
  final List<MilkRecord> records;
  
  const MilkStatsWidget({super.key, required this.records});
  
  @override
  Widget build(BuildContext context) {
    // Memoize expensive calculation
    final stats = useMemoized(
      () => _calculateStats(records),
      [records],
    );
    
    return Column(
      children: [
        Text('Average: ${stats.average}L'),
        Text('Total: ${stats.total}L'),
      ],
    );
  }
  
  Stats _calculateStats(List<MilkRecord> records) {
    // Expensive calculation
    final total = records.fold(0.0, (sum, r) => sum + r.volume);
    return Stats(
      total: total,
      average: records.isEmpty ? 0 : total / records.length,
    );
  }
}
```

### 4.4 60fps Target Compliance

```dart
// Performance-aware widget
class PerformanceAwareWidget extends StatefulWidget {
  @override
  _PerformanceAwareWidgetState createState() => _PerformanceAwareWidgetState();
}

class _PerformanceAwareWidgetState extends State<PerformanceAwareWidget> {
  late TimelineTask _timelineTask;
  
  @override
  void initState() {
    super.initState();
    _timelineTask = TimelineTask(filterKey: 'build_performance');
  }
  
  @override
  void dispose() {
    _timelineTask.finish();
    super.dispose();
  }
  
  @override
  Widget build(BuildContext context) {
    _timelineTask.start('build');
    
    final widget = Container(
      // Widget content
    );
    
    _timelineTask.finish();
    return widget;
  }
}
```

---

## 5. Memory Management

### 5.1 Leak Prevention

```dart
// ✅ Good: Properly dispose controllers and listeners
class MilkProductionForm extends StatefulWidget {
  @override
  _MilkProductionFormState createState() => _MilkProductionFormState();
}

class _MilkProductionFormState extends State<MilkProductionForm> {
  final TextEditingController _volumeController = TextEditingController();
  final FocusNode _focusNode = FocusNode();
  StreamSubscription? _dataSubscription;
  
  @override
  void initState() {
    super.initState();
    _dataSubscription = DataService.dataStream.listen(_onData);
  }
  
  @override
  void dispose() {
    // Always dispose resources
    _volumeController.dispose();
    _focusNode.dispose();
    _dataSubscription?.cancel();
    super.dispose();
  }
  
  void _onData(DataEvent event) {
    if (mounted) {
      setState(() {
        // Update state only if mounted
      });
    }
  }
  
  @override
  Widget build(BuildContext context) {
    return TextField(
      controller: _volumeController,
      focusNode: _focusNode,
    );
  }
}
```

### 5.2 Image Memory Management

```dart
// ✅ Good: Control image cache
class ImageCacheManager {
  static void configureCache() {
    // Set maximum image cache size
    PaintingBinding.instance.imageCache.maximumSize = 100; // images
    PaintingBinding.instance.imageCache.maximumSizeBytes = 50 * 1024 * 1024; // 50MB
  }
  
  static void clearCache() {
    PaintingBinding.instance.imageCache.clear();
    PaintingBinding.instance.imageCache.clearLiveImages();
  }
  
  static void evictImage(String url) {
    PaintingBinding.instance.imageCache.evict(NetworkImage(url));
  }
}

// ✅ Good: Use ResizeImage for large images
class OptimizedImage extends StatelessWidget {
  final String imageUrl;
  final double width;
  final double height;
  
  const OptimizedImage({
    super.key,
    required this.imageUrl,
    required this.width,
    required this.height,
  });
  
  @override
  Widget build(BuildContext context) {
    return Image(
      image: ResizeImage(
        NetworkImage(imageUrl),
        width: width.toInt(),
        height: height.toInt(),
        policy: ResizeImagePolicy.fit,
      ),
    );
  }
}
```

### 5.3 List Memory Management

```dart
// ✅ Good: Dispose list items properly
class CowListPage extends StatefulWidget {
  @override
  _CowListPageState createState() => _CowListPageState();
}

class _CowListPageState extends State<CowListPage> {
  final List<Cow> _cows = [];
  final Map<String, VideoController> _videoControllers = {};
  
  @override
  void dispose() {
    // Dispose all controllers
    for (final controller in _videoControllers.values) {
      controller.dispose();
    }
    _videoControllers.clear();
    super.dispose();
  }
  
  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      itemCount: _cows.length,
      itemBuilder: (context, index) {
        return CowListItem(
          cow: _cows[index],
          onVideoControllerCreated: (controller) {
            _videoControllers[_cows[index].id] = controller;
          },
        );
      },
    );
  }
}
```

### 5.4 Memory Profiling Helper

```dart
class MemoryProfiler {
  static void startProfiling() {
    if (kDebugMode) {
      Timeline.startSync('Memory Profile');
      
      // Log current memory usage
      final info = PlatformDispatcher.instance.views.first;
      debugPrint('Memory: ${info.physicalSize}');
    }
  }
  
  static void logMemoryUsage(String tag) {
    if (kDebugMode) {
      // Using dart:developer for memory info
      postEvent('Memory Usage', {
        'tag': tag,
        'timestamp': DateTime.now().toIso8601String(),
      });
    }
  }
  
  static void endProfiling() {
    if (kDebugMode) {
      Timeline.finishSync();
    }
  }
}
```

---

## 6. Image Optimization

### 6.1 Format Selection

| Format | Use Case | Compression |
|--------|----------|-------------|
| WebP | Android photos | Lossy, 25-35% smaller than JPEG |
| JPEG | iOS photos, no transparency | Adjustable quality 80-90% |
| PNG | Icons, transparency required | Lossless |
| HEIC | iOS photos (iOS 11+) | Better than JPEG |

```dart
// Smart image format selector
class SmartImage extends StatelessWidget {
  final String imagePath;
  final BoxFit fit;
  
  const SmartImage({
    super.key,
    required this.imagePath,
    this.fit = BoxFit.cover,
  });
  
  @override
  Widget build(BuildContext context) {
    final platform = Theme.of(context).platform;
    final optimizedPath = _getOptimizedPath(imagePath, platform);
    
    return Image.asset(
      optimizedPath,
      fit: fit,
      cacheWidth: _calculateCacheWidth(context),
      cacheHeight: _calculateCacheHeight(context),
    );
  }
  
  String _getOptimizedPath(String path, TargetPlatform platform) {
    switch (platform) {
      case TargetPlatform.android:
        return path.replaceAll('.png', '.webp');
      case TargetPlatform.iOS:
        return path; // Use original or HEIC variant
      default:
        return path;
    }
  }
  
  int? _calculateCacheWidth(BuildContext context) {
    final pixelRatio = MediaQuery.of(context).devicePixelRatio;
    final screenWidth = MediaQuery.of(context).size.width;
    return (screenWidth * pixelRatio).toInt();
  }
  
  int? _calculateCacheHeight(BuildContext context) {
    final pixelRatio = MediaQuery.of(context).devicePixelRatio;
    final screenHeight = MediaQuery.of(context).size.height;
    return (screenHeight * pixelRatio).toInt();
  }
}
```

### 6.2 Caching Strategy

```dart
// Advanced image caching
class ImageCacheService {
  final CacheManager _cacheManager = DefaultCacheManager();
  
  Future<File> getImage(String url, {
    int? maxAgeDays,
    Map<String, String>? headers,
  }) async {
    final fileInfo = await _cacheManager.getFileFromCache(url);
    
    if (fileInfo != null) {
      // Check if cache is still valid
      if (maxAgeDays == null || 
          DateTime.now().difference(fileInfo.validTill).inDays < maxAgeDays) {
        return fileInfo.file;
      }
    }
    
    // Download and cache
    return (await _cacheManager.downloadFile(
      url,
      authHeaders: headers,
    )).file;
  }
  
  Future<void> preCacheImages(List<String> urls) async {
    for (final url in urls) {
      try {
        await _cacheManager.downloadFile(url);
      } catch (e) {
        debugPrint('Failed to pre-cache: $url');
      }
    }
  }
  
  Future<void> clearExpiredCache() async {
    await _cacheManager.emptyCache();
  }
}

// Usage with CachedNetworkImage
class CachedCowImage extends StatelessWidget {
  final String imageUrl;
  final String cowName;
  
  const CachedCowImage({
    super.key,
    required this.imageUrl,
    required this.cowName,
  });
  
  @override
  Widget build(BuildContext context) {
    return CachedNetworkImage(
      imageUrl: imageUrl,
      placeholder: (context, url) => const CircularProgressIndicator(),
      errorWidget: (context, url, error) => const Icon(Icons.error),
      memCacheWidth: 300,
      memCacheHeight: 300,
      maxAgeDiskCache: const Duration(days: 7),
      fadeInDuration: const Duration(milliseconds: 300),
    );
  }
}
```

### 6.3 Lazy Loading

```dart
// Lazy image loading with intersection observer pattern
class LazyImageList extends StatelessWidget {
  final List<String> imageUrls;
  
  const LazyImageList({super.key, required this.imageUrls});
  
  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      itemCount: imageUrls.length,
      itemBuilder: (context, index) {
        return VisibilityDetector(
          key: Key('image_$index'),
          onVisibilityChanged: (visibilityInfo) {
            if (visibilityInfo.visibleFraction > 0.1) {
              // Load image when 10% visible
              ImagePrefetchService.prefetch(imageUrls[index]);
            }
          },
          child: LazyImage(url: imageUrls[index]),
        );
      },
    );
  }
}

class LazyImage extends StatefulWidget {
  final String url;
  
  const LazyImage({super.key, required this.url});
  
  @override
  _LazyImageState createState() => _LazyImageState();
}

class _LazyImageState extends State<LazyImage> {
  bool _shouldLoad = false;
  
  @override
  Widget build(BuildContext context) {
    return _shouldLoad
        ? CachedNetworkImage(imageUrl: widget.url)
        : Container(
            height: 200,
            color: Colors.grey[300],
            child: const Center(child: Icon(Icons.image)),
          );
  }
  
  void load() {
    if (!_shouldLoad) {
      setState(() => _shouldLoad = true);
    }
  }
}
```

---

## 7. Network Optimization

### 7.1 Request Batching

```dart
// Request batching service
class BatchedRequestService {
  final List<PendingRequest> _pendingRequests = [];
  Timer? _batchTimer;
  
  Future<T> queueRequest<T>({
    required String endpoint,
    required Map<String, dynamic> params,
    required T Function(dynamic) parser,
  }) {
    final completer = Completer<T>();
    
    _pendingRequests.add(PendingRequest(
      endpoint: endpoint,
      params: params,
      completer: completer,
      parser: parser,
    ));
    
    _scheduleBatch();
    
    return completer.future;
  }
  
  void _scheduleBatch() {
    _batchTimer?.cancel();
    _batchTimer = Timer(const Duration(milliseconds: 50), _executeBatch);
  }
  
  Future<void> _executeBatch() async {
    if (_pendingRequests.isEmpty) return;
    
    final requests = List<PendingRequest>.from(_pendingRequests);
    _pendingRequests.clear();
    
    // Group by endpoint
    final grouped = groupBy(requests, (r) => r.endpoint);
    
    for (final entry in grouped.entries) {
      try {
        final response = await _sendBatchRequest(
          entry.key,
          entry.value.map((r) => r.params).toList(),
        );
        
        // Distribute results
        for (int i = 0; i < entry.value.length; i++) {
          entry.value[i].completer.complete(
            entry.value[i].parser(response[i]),
          );
        }
      } catch (e) {
        for (final request in entry.value) {
          request.completer.completeError(e);
        }
      }
    }
  }
  
  Future<List<dynamic>> _sendBatchRequest(
    String endpoint,
    List<Map<String, dynamic>> params,
  ) async {
    final response = await http.post(
      Uri.parse('$baseUrl/batch/$endpoint'),
      body: jsonEncode({'requests': params}),
      headers: {'Content-Type': 'application/json'},
    );
    
    return jsonDecode(response.body)['results'];
  }
}
```

### 7.2 Compression

```dart
// Request/Response compression
class CompressedHttpClient {
  final Client _client = Client();
  
  Future<Response> postCompressed(
    String url, {
    Map<String, dynamic>? body,
    Map<String, String>? headers,
  }) async {
    final jsonBody = jsonEncode(body);
    final compressed = gzip.encode(utf8.encode(jsonBody));
    
    final response = await _client.post(
      Uri.parse(url),
      body: compressed,
      headers: {
        ...?headers,
        'Content-Encoding': 'gzip',
        'Accept-Encoding': 'gzip, deflate',
        'Content-Type': 'application/json',
      },
    );
    
    return _decompressResponse(response);
  }
  
  Response _decompressResponse(Response response) {
    final encoding = response.headers['content-encoding'];
    
    if (encoding == 'gzip') {
      final decompressed = gzip.decode(response.bodyBytes);
      return Response.bytes(
        decompressed,
        response.statusCode,
        headers: response.headers,
      );
    }
    
    return response;
  }
}
```

### 7.3 Connection Pooling

```dart
// Optimized HTTP client with connection pooling
class OptimizedHttpClient {
  static final OptimizedHttpClient _instance = OptimizedHttpClient._internal();
  factory OptimizedHttpClient() => _instance;
  
  late final Client _client;
  
  OptimizedHttpClient._internal() {
    _client = Client();
    
    // Configure IO client for connection pooling
    if (!kIsWeb) {
      _client = IOClient(HttpClient()
        ..maxConnectionsPerHost = 10
        ..idleTimeout = const Duration(seconds: 30)
        ..connectionTimeout = const Duration(seconds: 10));
    }
  }
  
  Future<Response> get(
    String url, {
    Map<String, String>? headers,
    Duration timeout = const Duration(seconds: 30),
  }) async {
    return _client
        .get(Uri.parse(url), headers: headers)
        .timeout(timeout);
  }
  
  void dispose() {
    _client.close();
  }
}
```

### 7.4 Request Deduplication

```dart
// Deduplicate identical concurrent requests
class DeduplicatedRequestService {
  final Map<String, Future<dynamic>> _inFlightRequests = {};
  
  Future<T> request<T>({
    required String key,
    required Future<T> Function() fetch,
  }) async {
    // Return existing request if in-flight
    if (_inFlightRequests.containsKey(key)) {
      return await _inFlightRequests[key] as T;
    }
    
    // Create new request
    final request = fetch();
    _inFlightRequests[key] = request;
    
    try {
      final result = await request;
      return result;
    } finally {
      _inFlightRequests.remove(key);
    }
  }
}

// Usage
class MilkProductionRepository {
  final _deduplicator = DeduplicatedRequestService();
  
  Future<List<MilkRecord>> getRecords(String cowId) async {
    return _deduplicator.request(
      key: 'milk_records_$cowId',
      fetch: () => _fetchRecords(cowId),
    );
  }
  
  Future<List<MilkRecord>> _fetchRecords(String cowId) async {
    final response = await http.get(
      Uri.parse('$apiUrl/cows/$cowId/milk-records'),
    );
    return parseRecords(response.body);
  }
}
```

---

## 8. Database Performance

### 8.1 Query Optimization

```dart
// Optimized database queries
class OptimizedDatabaseService {
  final Database _db;
  
  OptimizedDatabaseService(this._db);
  
  // ✅ Good: Use indexes, limit results
  Future<List<MilkRecord>> getRecentRecords({
    required String cowId,
    int limit = 50,
    int offset = 0,
  }) async {
    final results = await _db.rawQuery('''
      SELECT * FROM milk_records 
      WHERE cow_id = ? 
      ORDER BY created_at DESC 
      LIMIT ? OFFSET ?
    ''', [cowId, limit, offset]);
    
    return results.map((r) => MilkRecord.fromMap(r)).toList();
  }
  
  // ✅ Good: Batch operations
  Future<void> batchInsertRecords(List<MilkRecord> records) async {
    final batch = _db.batch();
    
    for (final record in records) {
      batch.insert(
        'milk_records',
        record.toMap(),
        conflictAlgorithm: ConflictAlgorithm.replace,
      );
    }
    
    await batch.commit(noResult: true);
  }
  
  // ✅ Good: Use transactions
  Future<void> updateMultipleRecords(
    List<MilkRecord> records,
  ) async {
    await _db.transaction((txn) async {
      for (final record in records) {
        await txn.update(
          'milk_records',
          record.toMap(),
          where: 'id = ?',
          whereArgs: [record.id],
        );
      }
    });
  }
  
  // ✅ Good: Precompiled statements
  Future<List<MilkRecord>> searchRecords(String query) async {
    final statement = await _db.prepare('''
      SELECT * FROM milk_records 
      WHERE cow_id LIKE ? OR notes LIKE ?
      ORDER BY created_at DESC
    ''');
    
    final results = await statement.select(['%$query%', '%$query%']);
    return results.map((r) => MilkRecord.fromMap(r)).toList();
  }
}
```

### 8.2 Indexing Strategy

```sql
-- Database migration for indexes
-- migrations/002_add_indexes.sql

-- Primary lookup indexes
CREATE INDEX IF NOT EXISTS idx_milk_records_cow_id 
  ON milk_records(cow_id);

CREATE INDEX IF NOT EXISTS idx_milk_records_created_at 
  ON milk_records(created_at DESC);

-- Composite index for common query patterns
CREATE INDEX IF NOT EXISTS idx_milk_records_cow_created 
  ON milk_records(cow_id, created_at DESC);

-- Partial index for active records
CREATE INDEX IF NOT EXISTS idx_milk_records_active 
  ON milk_records(cow_id) 
  WHERE deleted_at IS NULL;

-- Index for full-text search (if supported)
CREATE VIRTUAL TABLE IF NOT EXISTS milk_records_fts USING fts5(
  cow_id, notes, content='milk_records', content_rowid='id'
);
```

### 8.3 Connection Management

```dart
// Connection pooling for SQLite
class DatabaseConnectionPool {
  final String path;
  final int maxConnections;
  final List<Database> _pool = [];
  final Queue<Completer<Database>> _waiters = Queue();
  int _activeCount = 0;
  
  DatabaseConnectionPool({
    required this.path,
    this.maxConnections = 5,
  });
  
  Future<Database> acquire() async {
    // Return available connection
    if (_pool.isNotEmpty) {
      return _pool.removeLast();
    }
    
    // Create new connection if under limit
    if (_activeCount < maxConnections) {
      _activeCount++;
      return await openDatabase(
        path,
        version: 1,
        onCreate: _onCreate,
      );
    }
    
    // Wait for connection
    final completer = Completer<Database>();
    _waiters.add(completer);
    return completer.future;
  }
  
  void release(Database db) {
    if (_waiters.isNotEmpty) {
      _waiters.removeFirst().complete(db);
    } else {
      _pool.add(db);
    }
  }
  
  Future<void> close() async {
    for (final db in _pool) {
      await db.close();
    }
    _pool.clear();
  }
}
```

### 8.4 Data Caching Layer

```dart
// Multi-layer caching
class DataCacheService {
  final _memoryCache = LRUCache<String, dynamic>(capacity: 100);
  final Database _db;
  
  Future<T?> get<T>({
    required String key,
    required Future<T?> Function() fetch,
    Duration ttl = const Duration(minutes: 5),
  }) async {
    // Check memory cache
    final memoryValue = _memoryCache.get(key);
    if (memoryValue != null) {
      return memoryValue as T;
    }
    
    // Check disk cache
    final diskValue = await _getFromDisk(key);
    if (diskValue != null) {
      _memoryCache.put(key, diskValue);
      return diskValue as T;
    }
    
    // Fetch from network
    final freshValue = await fetch();
    if (freshValue != null) {
      await _putToCache(key, freshValue, ttl);
    }
    
    return freshValue;
  }
  
  Future<void> _putToCache(
    String key,
    dynamic value,
    Duration ttl,
  ) async {
    _memoryCache.put(key, value);
    
    await _db.insert(
      'cache',
      {
        'key': key,
        'value': jsonEncode(value),
        'expires_at': DateTime.now().add(ttl).toIso8601String(),
      },
      conflictAlgorithm: ConflictAlgorithm.replace,
    );
  }
  
  Future<dynamic> _getFromDisk(String key) async {
    final result = await _db.query(
      'cache',
      where: 'key = ? AND expires_at > ?',
      whereArgs: [key, DateTime.now().toIso8601String()],
      limit: 1,
    );
    
    if (result.isEmpty) return null;
    
    return jsonDecode(result.first['value'] as String);
  }
}

// LRU Cache implementation
class LRUCache<K, V> {
  final int capacity;
  final LinkedHashMap<K, V> _cache = LinkedHashMap();
  
  LRUCache({required this.capacity});
  
  V? get(K key) {
    if (!_cache.containsKey(key)) return null;
    
    // Move to end (most recently used)
    final value = _cache.remove(key);
    _cache[key] = value as V;
    return value;
  }
  
  void put(K key, V value) {
    if (_cache.containsKey(key)) {
      _cache.remove(key);
    } else if (_cache.length >= capacity) {
      // Remove least recently used
      _cache.remove(_cache.keys.first);
    }
    _cache[key] = value;
  }
}
```

---

## 9. Startup Time

### 9.1 Lazy Loading

```dart
// Deferred loading of heavy dependencies
class AppInitializer {
  static final _initializationTasks = <String, Future<void> Function()>{
    'core': _initializeCore,
    'analytics': _initializeAnalytics,
    'notifications': _initializeNotifications,
    'sync': _initializeSync,
  };
  
  static Future<void> initializeCore() async {
    // Essential initialization
    await _initializeCore();
    
    // Defer non-essential tasks
    _scheduleDeferredInitialization();
  }
  
  static void _scheduleDeferredInitialization() {
    // Run after first frame
    SchedulerBinding.instance.addPostFrameCallback((_) {
      // Initialize analytics after UI is ready
      _initializationTasks['analytics']?.call();
      
      // Initialize notifications with delay
      Future.delayed(const Duration(seconds: 2), () {
        _initializationTasks['notifications']?.call();
      });
    });
  }
  
  static Future<void> _initializeCore() async {
    // Minimal required initialization
    WidgetsFlutterBinding.ensureInitialized();
    await Firebase.initializeApp();
    await LocalStorage.initialize();
  }
  
  static Future<void> _initializeAnalytics() async {
    await FirebaseAnalytics.instance.setAnalyticsCollectionEnabled(true);
  }
  
  static Future<void> _initializeNotifications() async {
    await NotificationService.initialize();
  }
  
  static Future<void> _initializeSync() async {
    await SyncService.initialize();
  }
}
```

### 9.2 Deferred Components

```dart
// Split app into deferred loading zones
class DeferredApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      initialRoute: '/',
      routes: {
        '/': (context) => const SplashPage(),
        '/home': (context) => const HomePage(),
      },
      onGenerateRoute: (settings) {
        // Defer loading of feature modules
        if (settings.name?.startsWith('/analytics') == true) {
          return MaterialPageRoute(
            builder: (context) => FutureBuilder(
              future: loadAnalyticsModule(),
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.done) {
                  return const AnalyticsPage();
                }
                return const LoadingPage();
              },
            ),
          );
        }
        return null;
      },
    );
  }
  
  static Future<void> loadAnalyticsModule() async {
    // Load analytics module on demand
    await Future.wait([
      precacheImage(
        const AssetImage('assets/images/analytics_banner.png'),
        navigatorKey.currentContext!,
      ),
      AnalyticsService.initialize(),
    ]);
  }
}
```

### 9.3 Resource Preloading

```dart
// Strategic resource preloading
class ResourcePreloader {
  static final List<String> _criticalAssets = [
    'assets/images/logo.png',
    'assets/images/splash_bg.jpg',
    'assets/fonts/Roboto-Regular.ttf',
  ];
  
  static final List<String> _secondaryAssets = [
    'assets/images/cow_placeholder.png',
    'assets/icons/navigation/home.svg',
    'assets/icons/navigation/settings.svg',
  ];
  
  static Future<void> preloadCritical(BuildContext context) async {
    final futures = <Future>[];
    
    for (final asset in _criticalAssets) {
      if (asset.endsWith('.png') || asset.endsWith('.jpg')) {
        futures.add(precacheImage(AssetImage(asset), context));
      }
    }
    
    await Future.wait(futures);
  }
  
  static void preloadSecondary(BuildContext context) {
    SchedulerBinding.instance.addPostFrameCallback((_) async {
      for (final asset in _secondaryAssets) {
        if (asset.endsWith('.png') || asset.endsWith('.jpg')) {
          precacheImage(AssetImage(asset), context);
        }
      }
    });
  }
}

// Usage in splash screen
class SplashPage extends StatefulWidget {
  @override
  _SplashPageState createState() => _SplashPageState();
}

class _SplashPageState extends State<SplashPage> {
  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    // Preload while showing splash
    ResourcePreloader.preloadCritical(context);
  }
  
  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      body: Center(child: CircularProgressIndicator()),
    );
  }
}
```

### 9.4 Startup Measurement

```dart
// Startup time measurement
class StartupTimer {
  static final Stopwatch _totalTimer = Stopwatch();
  static final Map<String, Stopwatch> _phaseTimers = {};
  
  static void start() {
    _totalTimer.start();
    debugPrint('🚀 App startup started');
  }
  
  static void startPhase(String phase) {
    _phaseTimers[phase] = Stopwatch()..start();
    debugPrint('⏱️ Phase "$phase" started');
  }
  
  static void endPhase(String phase) {
    final timer = _phaseTimers[phase];
    if (timer != null) {
      timer.stop();
      debugPrint('✅ Phase "$phase" completed in ${timer.elapsedMilliseconds}ms');
      
      // Report to analytics
      FirebasePerformance.instance
          .newTrace('startup_$phase')
          .start()
          .then((trace) => trace.stop());
    }
  }
  
  static void complete() {
    _totalTimer.stop();
    final totalMs = _totalTimer.elapsedMilliseconds;
    debugPrint('🎉 Total startup time: ${totalMs}ms');
    
    // Alert if over budget
    if (totalMs > 3000) {
      debugPrint('⚠️ Startup time exceeds budget (3000ms)!');
    }
  }
}

// In main.dart
void main() {
  StartupTimer.start();
  
  StartupTimer.startPhase('initialization');
  // ... initialization code
  StartupTimer.endPhase('initialization');
  
  StartupTimer.startPhase('firebase');
  // ... Firebase setup
  StartupTimer.endPhase('firebase');
  
  runApp(const SmartDairyApp());
  
  StartupTimer.complete();
}
```

---

## 10. Battery Optimization

### 10.1 Background Tasks

```dart
// Optimized background task scheduling
class BackgroundTaskManager {
  static final Workmanager _workmanager = Workmanager();
  
  static Future<void> initialize() async {
    await _workmanager.initialize(
      callbackDispatcher,
      isInDebugMode: kDebugMode,
    );
  }
  
  static void callbackDispatcher() {
    Workmanager().executeTask((task, inputData) async {
      switch (task) {
        case 'sync_data':
          return await _performSync();
        case 'cleanup_cache':
          return await _performCleanup();
        default:
          return Future.value(true);
      }
    });
  }
  
  static Future<bool> _performSync() async {
    try {
      // Batch sync operations
      await SyncService.syncPendingData();
      
      // Report success
      return true;
    } catch (e) {
      debugPrint('Background sync failed: $e');
      return false;
    }
  }
  
  static void schedulePeriodicSync() {
    // Use battery-aware constraints
    _workmanager.registerPeriodicTask(
      'periodic_sync',
      'sync_data',
      frequency: const Duration(hours: 1),
      constraints: Constraints(
        networkType: NetworkType.connected,
        requiresBatteryNotLow: true,
        requiresDeviceIdle: false, // Allow while in use
      ),
      existingWorkPolicy: ExistingWorkPolicy.keep,
    );
  }
  
  static void scheduleDailyCleanup() {
    _workmanager.registerPeriodicTask(
      'daily_cleanup',
      'cleanup_cache',
      frequency: const Duration(days: 1),
      constraints: Constraints(
        requiresCharging: true, // Only when charging
        requiresDeviceIdle: true, // Only when idle
      ),
    );
  }
}
```

### 10.2 GPS Optimization

```dart
// Battery-efficient location tracking
class BatteryAwareLocationService {
  static const LocationSettings _highAccuracy = LocationSettings(
    accuracy: LocationAccuracy.best,
    distanceFilter: 10, // meters
  );
  
  static const LocationSettings _balanced = LocationSettings(
    accuracy: LocationAccuracy.balanced,
    distanceFilter: 50,
  );
  
  static const LocationSettings _lowPower = LocationSettings(
    accuracy: LocationAccuracy.low,
    distanceFilter: 100,
  );
  
  StreamSubscription<Position>? _positionStream;
  
  void startTracking(LocationPriority priority) {
    final settings = _getSettings(priority);
    
    _positionStream?.cancel();
    _positionStream = Geolocator.getPositionStream(
      locationSettings: settings,
    ).listen(_onPositionUpdate);
  }
  
  LocationSettings _getSettings(LocationPriority priority) {
    switch (priority) {
      case LocationPriority.high:
        return _highAccuracy;
      case LocationPriority.balanced:
        return _balanced;
      case LocationPriority.lowPower:
        return _lowPower;
    }
  }
  
  void stopTracking() {
    _positionStream?.cancel();
    _positionStream = null;
  }
  
  // Fetch location on-demand instead of continuous tracking
  Future<Position> getCurrentLocationOnce() async {
    return await Geolocator.getCurrentPosition(
      desiredAccuracy: LocationAccuracy.medium,
    );
  }
}

enum LocationPriority { high, balanced, lowPower }
```

### 10.3 Network Batch Sync

```dart
// Batch network operations to reduce radio wake-ups
class BatchSyncManager {
  Timer? _syncTimer;
  final List<SyncOperation> _pendingOperations = [];
  
  void queueOperation(SyncOperation operation) {
    _pendingOperations.add(operation);
    _scheduleBatchSync();
  }
  
  void _scheduleBatchSync() {
    _syncTimer?.cancel();
    _syncTimer = Timer(const Duration(seconds: 30), _executeBatch);
  }
  
  Future<void> _executeBatch() async {
    if (_pendingOperations.isEmpty) return;
    
    final operations = List<SyncOperation>.from(_pendingOperations);
    _pendingOperations.clear();
    
    // Group by type
    final grouped = groupBy(operations, (op) => op.type);
    
    // Execute in single network session
    await NetworkService.executeBatch(grouped);
  }
  
  void dispose() {
    _syncTimer?.cancel();
  }
}
```

### 10.4 Doze Mode Handling

```dart
// Handle Doze mode and App Standby
class DozeModeHandler {
  static const platform = MethodChannel('battery_optimization');
  
  // Check if app is ignoring battery optimizations
  static Future<bool> isIgnoringOptimizations() async {
    if (Platform.isAndroid) {
      try {
        return await platform.invokeMethod('isIgnoringBatteryOptimizations');
      } catch (e) {
        return false;
      }
    }
    return true;
  }
  
  // Request exemption from Doze mode
  static Future<void> requestIgnoreOptimizations() async {
    if (Platform.isAndroid) {
      try {
        await platform.invokeMethod('requestIgnoreBatteryOptimizations');
      } catch (e) {
        debugPrint('Failed to request battery optimization exemption: $e');
      }
    }
  }
  
  // Use high-priority FCM for critical notifications
  static void handleCriticalNotification(RemoteMessage message) {
    // High priority messages bypass Doze mode
    final notification = message.notification;
    if (notification != null) {
      NotificationService.showHighPriority(notification);
    }
  }
}
```

---

## 11. Profiling Tools

### 11.1 Flutter DevTools

#### Setup and Launch

```bash
# Start DevTools
flutter pub global activate devtools
flutter pub global run devtools

# Or use IDE integration (VS Code/Android Studio)
# Run app in profile mode
flutter run --profile
```

#### Key DevTools Features

```dart
// Performance overlay helper
class PerformanceOverlayToggle extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return FloatingActionButton(
      onPressed: () {
        // Toggle performance overlay programmatically
        debugProfileBuildsEnabled = !debugProfileBuildsEnabled;
      },
      child: const Icon(Icons.speed),
    );
  }
}

// Add performance overlay to app
class SmartDairyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      showPerformanceOverlay: true, // Enable in profile mode
      checkerboardRasterCacheImages: true, // Debug image caching
      checkerboardOffscreenLayers: true, // Debug layer usage
      home: const HomePage(),
    );
  }
}
```

### 11.2 Performance Overlay Guide

| Color Bar | Meaning | Target |
|-----------|---------|--------|
| **Top (GPU)** | Raster thread time | < 16ms (green) |
| **Bottom (UI)** | Build time | < 16ms (green) |
| **Red bars** | Missed frame | Avoid |

```dart
// Interpret performance overlay
class PerformanceOverlayGuide {
  static void analyzePerformance(List<FrameTiming> timings) {
    for (final timing in timings) {
      final buildTime = timing.buildDuration.inMicroseconds / 1000;
      final rasterTime = timing.rasterDuration.inMicroseconds / 1000;
      final totalTime = timing.totalSpan.inMicroseconds / 1000;
      
      if (totalTime > 16.67) {
        debugPrint('⚠️ Jank detected!');
        debugPrint('  Build: ${buildTime.toStringAsFixed(2)}ms');
        debugPrint('  Raster: ${rasterTime.toStringAsFixed(2)}ms');
        debugPrint('  Total: ${totalTime.toStringAsFixed(2)}ms');
        
        if (buildTime > 16.67) {
          debugPrint('  → Issue: UI thread (build method too heavy)');
        } else if (rasterTime > 16.67) {
          debugPrint('  → Issue: GPU thread (too many pixels/shaders)');
        }
      }
    }
  }
}
```

### 11.3 Timeline Events

```dart
// Custom timeline events for profiling
class TimelineProfiler {
  static void startBuild(String widgetName) {
    Timeline.startSync('Build $widgetName', 
      arguments: {'type': 'widget_build'});
  }
  
  static void endBuild() {
    Timeline.finishSync();
  }
  
  static Future<T> profileAsync<T>(
    String operation,
    Future<T> Function() action,
  ) async {
    final trace = TimelineTask(filterKey: operation);
    trace.start(operation);
    
    try {
      final result = await action();
      trace.finish();
      return result;
    } catch (e) {
      trace.finish();
      rethrow;
    }
  }
  
  static T profileSync<T>(String operation, T Function() action) {
    Timeline.startSync(operation);
    try {
      return action();
    } finally {
      Timeline.finishSync();
    }
  }
}

// Usage
Widget buildHeavyWidget() {
  TimelineProfiler.startBuild('HeavyWidget');
  
  final widget = Container(
    // Complex build
  );
  
  TimelineProfiler.endBuild();
  return widget;
}
```

### 11.4 Memory Profiling

```dart
// Memory snapshot helper
class MemorySnapshot {
  static void capture(String tag) {
    if (kDebugMode) {
      // Force garbage collection before measurement
      Timeline.instantSync('GC', arguments: {'reason': 'memory_snapshot'});
      
      // Log current memory state
      postEvent('MemorySnapshot', {
        'tag': tag,
        'timestamp': DateTime.now().toIso8601String(),
        'rss': ProcessInfo.currentRss,
      });
    }
  }
  
  static void compare(String tag1, String tag2) {
    // Compare memory between two snapshots
    debugPrint('Comparing memory: $tag1 vs $tag2');
  }
}

// Memory leak detection
class MemoryLeakDetector {
  static final Map<String, WeakReference> _trackedObjects = {};
  
  static void track(Object object, String name) {
    _trackedObjects[name] = WeakReference(object);
  }
  
  static void checkForLeaks() {
    _trackedObjects.forEach((name, ref) {
      if (ref.target != null) {
        debugPrint('⚠️ Potential leak: $name is still referenced');
      }
    });
  }
}
```

---

## 12. Performance Monitoring

### 12.1 Firebase Performance

```dart
// Firebase Performance Monitoring setup
class PerformanceMonitoring {
  static final FirebasePerformance _performance = FirebasePerformance.instance;
  
  static Future<void> initialize() async {
    await _performance.setPerformanceCollectionEnabled(true);
  }
  
  // HTTP request monitoring (automatic)
  static HttpClient getMonitoredClient() {
    return _performance.newHttpClient(HttpClient());
  }
  
  // Custom traces
  static Future<T> trace<T>(
    String name,
    Future<T> Function() operation, {
    Map<String, String>? attributes,
  }) async {
    final trace = _performance.newTrace(name);
    await trace.start();
    
    attributes?.forEach((key, value) {
      trace.putAttribute(key, value);
    });
    
    try {
      final result = await operation();
      trace.putAttribute('success', 'true');
      return result;
    } catch (e) {
      trace.putAttribute('success', 'false');
      trace.putAttribute('error', e.toString());
      rethrow;
    } finally {
      await trace.stop();
    }
  }
  
  // Screen load tracking
  static void trackScreenLoad(String screenName, int loadTimeMs) {
    final trace = _performance.newTrace('screen_load_$screenName');
    trace.setMetric('load_time_ms', loadTimeMs.toDouble());
  }
}

// Usage in widgets
class MonitoredPage extends StatefulWidget {
  @override
  _MonitoredPageState createState() => _MonitoredPageState();
}

class _MonitoredPageState extends State<MonitoredPage> {
  late Trace _pageTrace;
  
  @override
  void initState() {
    super.initState();
    _pageTrace = PerformanceMonitoring._performance.newTrace('page_home');
    _pageTrace.start();
  }
  
  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    _pageTrace.putAttribute('rendered', 'true');
  }
  
  @override
  void dispose() {
    _pageTrace.stop();
    super.dispose();
  }
  
  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      body: Center(child: Text('Home')),
    );
  }
}
```

### 12.2 Custom Metrics

```dart
// Custom performance metrics
class CustomMetrics {
  static void recordFrameJank(int missedFrames) {
    FirebaseAnalytics.instance.logEvent(
      name: 'frame_jank',
      parameters: {
        'missed_frames': missedFrames,
        'timestamp': DateTime.now().millisecondsSinceEpoch,
      },
    );
  }
  
  static void recordMemoryPressure(int usedMB) {
    FirebaseAnalytics.instance.logEvent(
      name: 'memory_pressure',
      parameters: {
        'used_mb': usedMB,
        'threshold': 200,
      },
    );
  }
  
  static void recordSlowOperation(String operation, int durationMs) {
    if (durationMs > 1000) {
      FirebaseAnalytics.instance.logEvent(
        name: 'slow_operation',
        parameters: {
          'operation': operation,
          'duration_ms': durationMs,
        },
      );
    }
  }
}
```

### 12.3 Real User Monitoring

```dart
// Real user performance data
class RealUserMonitoring {
  static void initialize() {
    // Track app lifecycle for session metrics
    WidgetsBinding.instance.addObserver(AppLifecycleObserver());
    
    // Track frame performance
    SchedulerBinding.instance.addTimingsCallback(_onFrameTimings);
  }
  
  static void _onFrameTimings(List<FrameTiming> timings) {
    int jankFrames = 0;
    int totalFrames = timings.length;
    
    for (final timing in timings) {
      if (timing.totalSpan.inMilliseconds > 16) {
        jankFrames++;
      }
    }
    
    if (jankFrames > 0) {
      // Send to analytics (throttled)
      _throttledReport('frame_jank', {
        'jank_frames': jankFrames,
        'total_frames': totalFrames,
        'jank_ratio': jankFrames / totalFrames,
      });
    }
  }
  
  static DateTime? _lastReport;
  static void _throttledReport(String event, Map<String, dynamic> data) {
    final now = DateTime.now();
    if (_lastReport == null || 
        now.difference(_lastReport!).inSeconds > 60) {
      _lastReport = now;
      FirebaseAnalytics.instance.logEvent(
        name: event,
        parameters: data.map((k, v) => MapEntry(k, v.toString())),
      );
    }
  }
}

class AppLifecycleObserver extends WidgetsBindingObserver {
  DateTime? _sessionStart;
  
  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    switch (state) {
      case AppLifecycleState.resumed:
        _sessionStart = DateTime.now();
        break;
      case AppLifecycleState.paused:
        if (_sessionStart != null) {
          final duration = DateTime.now().difference(_sessionStart!);
          FirebaseAnalytics.instance.logEvent(
            name: 'session_duration',
            parameters: {'seconds': duration.inSeconds},
          );
        }
        break;
      default:
        break;
    }
  }
}
```

---

## 13. Benchmarking

### 13.1 Automated Performance Tests

```dart
// Integration test with performance measurement
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';

void main() {
  final binding = IntegrationTestWidgetsFlutterBinding.ensureInitialized();
  binding.framePolicy = LiveTestWidgetsFlutterBindingFramePolicy.fullyLive;
  
  group('Performance Benchmarks', () {
    testWidgets('Startup time benchmark', (tester) async {
      final stopwatch = Stopwatch()..start();
      
      await tester.pumpWidget(const SmartDairyApp());
      await tester.pumpAndSettle();
      
      stopwatch.stop();
      final startupTime = stopwatch.elapsedMilliseconds;
      
      expect(startupTime, lessThan(3000), 
        reason: 'Startup time should be under 3 seconds');
      
      // Report to CI
      await binding.convertFlutterSurfaceToImage();
      await binding.takeScreenshot('startup_benchmark');
    });
    
    testWidgets('Scroll performance benchmark', (tester) async {
      await tester.pumpWidget(const SmartDairyApp());
      await tester.pumpAndSettle();
      
      final listFinder = find.byType(ListView);
      
      // Measure scroll performance
      final timeline = await tester.traceAction(() async {
        await tester.fling(listFinder, const Offset(0, -500), 1000);
        await tester.pumpAndSettle();
      });
      
      // Analyze timeline
      final summary = TimelineSummary.summarize(timeline);
      final averageFrameTime = summary.computeAverageFrameBuildTimeMillis();
      final missedFrames = summary.countMissedFrames();
      
      expect(averageFrameTime, lessThan(16.0),
        reason: 'Average frame time should be under 16ms');
      expect(missedFrames, lessThan(10),
        reason: 'Should have minimal missed frames');
      
      // Write timeline for analysis
      await summary.writeTimelineToFile('scroll_timeline', pretty: true);
    });
    
    testWidgets('Memory usage benchmark', (tester) async {
      await tester.pumpWidget(const SmartDairyApp());
      await tester.pumpAndSettle();
      
      // Perform typical operations
      await tester.tap(find.text('Milk Production'));
      await tester.pumpAndSettle();
      
      await tester.tap(find.text('Add Record'));
      await tester.pumpAndSettle();
      
      // Memory should be under 200MB
      // Note: Actual memory measurement requires platform-specific code
    });
  });
}
```

### 13.2 Performance Regression Tests

```dart
// Performance regression detection
class PerformanceRegressionTest {
  static const String _baselineKey = 'perf_baseline';
  
  static Future<void> recordBaseline(String test, double value) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setDouble('${_baselineKey}_$test', value);
  }
  
  static Future<bool> checkRegression(String test, double current) async {
    final prefs = await SharedPreferences.getInstance();
    final baseline = prefs.getDouble('${_baselineKey}_$test');
    
    if (baseline == null) {
      await recordBaseline(test, current);
      return false;
    }
    
    // Allow 10% regression
    final threshold = baseline * 1.1;
    
    if (current > threshold) {
      debugPrint('⚠️ Performance regression detected!');
      debugPrint('  Test: $test');
      debugPrint('  Baseline: ${baseline.toStringAsFixed(2)}');
      debugPrint('  Current: ${current.toStringAsFixed(2)}');
      debugPrint('  Regression: ${((current - baseline) / baseline * 100).toStringAsFixed(2)}%');
      return true;
    }
    
    return false;
  }
}
```

### 13.3 Continuous Integration

```yaml
# .github/workflows/performance.yml
name: Performance Tests

on:
  pull_request:
    branches: [main]

jobs:
  performance:
    runs-on: macos-latest
    steps:
      - uses: actions/checkout@v3
      
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.x'
      
      - name: Install dependencies
        run: flutter pub get
      
      - name: Build profile APK
        run: flutter build apk --profile
      
      - name: Run performance tests
        run: flutter drive --target=test_driver/perf_test.dart --profile
      
      - name: Upload results
        uses: actions/upload-artifact@v3
        with:
          name: performance-results
          path: build/performance/
      
      - name: Check performance budget
        run: |
          ./scripts/check_performance_budget.sh
```

---

## 14. Best Practices

### 14.1 Coding Patterns

```dart
// ✅ Good: Immutable data classes
@immutable
class MilkRecord {
  final String id;
  final String cowId;
  final double volume;
  final DateTime timestamp;
  
  const MilkRecord({
    required this.id,
    required this.cowId,
    required this.volume,
    required this.timestamp,
  });
  
  // Value equality
  @override
  bool operator ==(Object other) =>
    identical(this, other) ||
    other is MilkRecord &&
    runtimeType == other.runtimeType &&
    id == other.id;
  
  @override
  int get hashCode => id.hashCode;
}

// ✅ Good: Efficient list operations
class DataProcessor {
  // Use generator for large lists
  Iterable<MilkRecord> filterLargeList(
    List<MilkRecord> records,
    String cowId,
  ) sync* {
    for (final record in records) {
      if (record.cowId == cowId) {
        yield record;
      }
    }
  }
  
  // Use efficient algorithms
  Map<String, List<MilkRecord>> groupByCow(
    List<MilkRecord> records,
  ) {
    return records.fold<Map<String, List<MilkRecord>>>(
      {},
      (map, record) {
        map.putIfAbsent(record.cowId, () => []).add(record);
        return map;
      },
    );
  }
}

// ✅ Good: Async/await patterns
class AsyncPatterns {
  // Parallel async operations
  Future<List<Data>> fetchMultiple(List<String> ids) async {
    final futures = ids.map((id) => fetchData(id));
    return await Future.wait(futures);
  }
  
  // Sequential with error handling
  Future<void> processSequential(List<Task> tasks) async {
    for (final task in tasks) {
      try {
        await processTask(task);
      } catch (e) {
        debugPrint('Failed to process ${task.id}: $e');
        // Continue with next task
      }
    }
  }
  
  // Timeout handling
  Future<Data> fetchWithTimeout(String id) async {
    return await fetchData(id).timeout(
      const Duration(seconds: 5),
      onTimeout: () => Data.defaultValue(),
    );
  }
}
```

### 14.2 Anti-Patterns to Avoid

```dart
// ❌ Bad: Unnecessary rebuilds
class BadExample extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      itemCount: 1000,
      itemBuilder: (context, index) {
        // This rebuilds every time parent rebuilds
        return Container(
          color: Provider.of<ThemeProvider>(context).primaryColor,
          child: Text('Item $index'),
        );
      },
    );
  }
}

// ✅ Good: Minimize rebuild scope
class GoodExample extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      itemCount: 1000,
      itemBuilder: (context, index) {
        return ItemWidget(index: index); // Separate widget
      },
    );
  }
}

class ItemWidget extends StatelessWidget {
  final int index;
  
  const ItemWidget({super.key, required this.index});
  
  @override
  Widget build(BuildContext context) {
    // Only this widget rebuilds when theme changes
    return Container(
      color: Provider.of<ThemeProvider>(context).primaryColor,
      child: Text('Item $index'),
    );
  }
}

// ❌ Bad: Blocking UI thread
Future<void> badProcess() async {
  final data = await fetchData();
  // Blocking computation on main thread
  final result = heavyComputation(data);
  updateUI(result);
}

// ✅ Good: Offload to isolate
Future<void> goodProcess() async {
  final data = await fetchData();
  // Computation in separate isolate
  final result = await compute(heavyComputation, data);
  updateUI(result);
}

// ❌ Bad: Memory leaks
class LeakyWidget extends StatefulWidget {
  @override
  _LeakyWidgetState createState() => _LeakyWidgetState();
}

class _LeakyWidgetState extends State<LeakyWidget> {
  StreamSubscription? _subscription;
  
  @override
  void initState() {
    super.initState();
    // ❌ Bad: Not cancelled
    DataService.stream.listen((data) {
      setState(() {}); // May crash if disposed
    });
  }
}

// ✅ Good: Proper cleanup
class CleanWidget extends StatefulWidget {
  @override
  _CleanWidgetState createState() => _CleanWidgetState();
}

class _CleanWidgetState extends State<CleanWidget> {
  StreamSubscription? _subscription;
  
  @override
  void initState() {
    super.initState();
    _subscription = DataService.stream.listen((data) {
      if (mounted) setState(() {});
    });
  }
  
  @override
  void dispose() {
    _subscription?.cancel();
    super.dispose();
  }
}
```

### 14.3 Performance Checklist

```dart
// Pre-release performance checklist widget
class PerformanceChecklist {
  static final List<ChecklistItem> items = [
    ChecklistItem(
      'Use const constructors where possible',
      () => _checkConstUsage(),
    ),
    ChecklistItem(
      'Verify ListView uses builder constructor',
      () => _checkListViewBuilders(),
    ),
    ChecklistItem(
      'Ensure images are appropriately sized',
      () => _checkImageSizes(),
    ),
    ChecklistItem(
      'Verify proper disposal of controllers',
      () => _checkDisposal(),
    ),
    ChecklistItem(
      'Check for unnecessary rebuilds',
      () => _checkRebuilds(),
    ),
    ChecklistItem(
      'Verify network request deduplication',
      () => _checkRequestDeduplication(),
    ),
    ChecklistItem(
      'Ensure database queries use indexes',
      () => _checkQueryIndexes(),
    ),
    ChecklistItem(
      'Verify background task constraints',
      () => _checkBackgroundTasks(),
    ),
  ];
  
  static Future<Map<String, bool>> runChecks() async {
    final results = <String, bool>{};
    
    for (final item in items) {
      results[item.name] = await item.check();
    }
    
    return results;
  }
}

class ChecklistItem {
  final String name;
  final Future<bool> Function() check;
  
  ChecklistItem(this.name, this.check);
}
```

---

## 15. Platform-Specific Tips

### 15.1 Android Optimization

```gradle
// android/app/build.gradle
android {
    defaultConfig {
        // Enable code shrinking
        minifyEnabled true
        shrinkResources true
        
        // ProGuard rules
        proguardFiles getDefaultProguardFile('proguard-android.txt'), 'proguard-rules.pro'
        
        // Split APK by ABI
        splits {
            abi {
                enable true
                reset()
                include 'armeabi-v7a', 'arm64-v8a', 'x86_64'
                universalApk false
            }
        }
    }
    
    buildTypes {
        release {
            // Additional optimization
            ndk {
                abiFilters 'arm64-v8a', 'armeabi-v7a'
            }
        }
    }
}
```

```xml
<!-- AndroidManifest.xml optimizations -->
<manifest>
    <!-- Reduce app startup time -->
    <application
        android:extractNativeLibs="true"
        android:largeHeap="false">
        
        <!-- Disable window preview for faster startup -->
        <activity
            android:name=".MainActivity"
            android:windowDisablePreview="true"
            android:exported="true">
        </activity>
    </application>
</manifest>
```

### 15.2 iOS Optimization

```swift
// ios/Runner/AppDelegate.swift optimizations
import Flutter
import UIKit

@UIApplicationMain
@objc class AppDelegate: FlutterAppDelegate {
    override func application(
        _ application: UIApplication,
        didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?
    ) -> Bool {
        // Reduce launch time
        GeneratedPluginRegistrant.register(with: self)
        
        // Optimize rendering
        if let window = self.window {
            window.backgroundColor = UIColor.white
        }
        
        return super.application(application, didFinishLaunchingWithOptions: launchOptions)
    }
    
    // Handle memory warnings
    override func applicationDidReceiveMemoryWarning(_ application: UIApplication) {
        // Clear caches
        URLCache.shared.removeAllCachedResponses()
    }
}
```

```ruby
# ios/Podfile optimizations
platform :ios, '12.0'

post_install do |installer|
  installer.pods_project.targets.each do |target|
    target.build_configurations.each do |config|
      config.build_settings['ENABLE_BITCODE'] = 'NO'
      config.build_settings['SWIFT_OPTIMIZATION_LEVEL'] = '-O'
      config.build_settings['GCC_OPTIMIZATION_LEVEL'] = 's' # Size optimization
    end
  end
end
```

### 15.3 Platform Channels

```dart
// Optimized platform channel usage
class OptimizedPlatformChannel {
  static const platform = MethodChannel('smart_dairy/optimized');
  static const eventChannel = EventChannel('smart_dairy/events');
  
  // Batch platform calls
  static Future<List<dynamic>> batchCall(
    List<Map<String, dynamic>> operations,
  ) async {
    return await platform.invokeMethod('batch', {
      'operations': operations,
    });
  }
  
  // Use event channels for streaming data
  static Stream<LocationUpdate> get locationStream {
    return eventChannel
        .receiveBroadcastStream()
        .map((event) => LocationUpdate.fromMap(event));
  }
}
```

---

## 16. Appendices

### Appendix A: Performance Checklist

#### Pre-Release Checklist

- [ ] App size under 50MB
- [ ] Cold startup under 3 seconds
- [ ] Warm startup under 1.5 seconds
- [ ] 60fps maintained during scrolling
- [ ] Memory usage under 200MB
- [ ] No memory leaks detected
- [ ] Images properly optimized and cached
- [ ] Network requests batched where appropriate
- [ ] Database queries use indexes
- [ ] Background tasks use proper constraints
- [ ] Battery impact minimized

#### Code Review Checklist

- [ ] `const` used for static widgets
- [ ] `ListView.builder` used for dynamic lists
- [ ] Controllers and listeners properly disposed
- [ ] No expensive operations in build methods
- [ ] Async gaps checked with `mounted`
- [ ] Images use appropriate cache dimensions
- [ ] Network requests have timeouts
- [ ] Database transactions used for batch operations

### Appendix B: Troubleshooting Guide

| Symptom | Likely Cause | Solution |
|---------|--------------|----------|
| Slow startup | Too many initializations | Defer non-essential tasks |
| Jank during scroll | Heavy build methods | Use const, split widgets |
| High memory usage | Large images not cached | Use ResizeImage, clear cache |
| Battery drain | Frequent GPS/network | Batch operations, use constraints |
| APK size too large | Unoptimized assets | Compress images, enable ProGuard |
| Slow database queries | Missing indexes | Add appropriate indexes |
| UI freezing | Blocking main thread | Use compute(), async operations |

### Appendix C: Performance Testing Scripts

```bash
#!/bin/bash
# scripts/performance_test.sh

echo "Running performance tests..."

# Build profile version
echo "Building profile APK..."
flutter build apk --profile --target-platform android-arm64

# Run integration tests
echo "Running integration tests..."
flutter drive \
  --driver=test_driver/integration_test.dart \
  --target=integration_test/perf_test.dart \
  --profile

# Analyze size
echo "Analyzing APK size..."
flutter build apk --analyze-size --target-platform android-arm64

echo "Performance tests complete!"
```

```yaml
# .github/workflows/performance.yml
name: Performance Benchmarks

on:
  schedule:
    - cron: '0 0 * * 0' # Weekly on Sunday

jobs:
  benchmark:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: subosito/flutter-action@v2
      
      - name: Install dependencies
        run: flutter pub get
      
      - name: Run benchmarks
        run: flutter test benchmark/
      
      - name: Upload benchmark results
        uses: benchmark-action/github-action-benchmark@v1
        with:
          tool: 'flutter'
          output-file-path: benchmark/results.json
          github-token: ${{ secrets.GITHUB_TOKEN }}
          auto-push: true
```

### Appendix D: DevTools Usage Guide

```dart
// DevTools integration helper
class DevToolsHelper {
  static void openDevTools() {
    if (kDebugMode) {
      // DevTools will auto-connect to running app
      debugPrint('Open DevTools: http://127.0.0.1:9100');
    }
  }
  
  static void enableAllDebugFeatures() {
    debugProfileBuildsEnabled = true;
    debugPaintSizeEnabled = true;
    debugPaintBaselinesEnabled = true;
    debugPaintLayerBordersEnabled = true;
    debugPaintPointersEnabled = true;
  }
  
  static void exportPerformanceData() {
    // Export timeline for analysis
    postEvent('Export Performance Data', {
      'timestamp': DateTime.now().toIso8601String(),
    });
  }
}
```

### Appendix E: Metric Thresholds Reference

```dart
// Performance thresholds configuration
class PerformanceThresholds {
  // Frame timing
  static const double targetFrameTimeMs = 16.67; // 60fps
  static const double warningFrameTimeMs = 33.33; // 30fps
  static const int maxMissedFramesPerSecond = 2;
  
  // Memory
  static const int maxMemoryMB = 200;
  static const int warningMemoryMB = 150;
  static const int criticalMemoryMB = 250;
  
  // Startup
  static const int maxColdStartMs = 3000;
  static const int maxWarmStartMs = 1500;
  static const int maxFirstFrameMs = 1000;
  
  // Network
  static const int maxApiResponseMs = 500;
  static const int maxImageLoadMs = 200;
  static const double minCacheHitRate = 0.85;
  
  // App size
  static const int maxAndroidApkMB = 50;
  static const int maxAndroidAabMB = 45;
  static const int maxIosIpaMB = 55;
  
  // Battery
  static const int maxBackgroundDrainPercentPerHour = 5;
  static const int maxGpsUpdatesPerMinute = 4;
}
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| **Author** | Mobile Lead | _________________ | January 31, 2026 |
| **Reviewer** | Tech Lead | _________________ | January 31, 2026 |
| **Approver** | CTO | _________________ | January 31, 2026 |

---

## References

1. [Flutter Performance Best Practices](https://docs.flutter.dev/perf)
2. [Flutter DevTools Documentation](https://docs.flutter.dev/development/tools/devtools)
3. [Android Performance Guidelines](https://developer.android.com/topic/performance)
4. [iOS Performance Guidelines](https://developer.apple.com/documentation/metrickit)
5. [Firebase Performance Monitoring](https://firebase.google.com/docs/perf-mon)

---

*Document ID: H-014 | Version: 1.0 | Date: January 31, 2026*

*Smart Dairy Ltd. - Confidential*
