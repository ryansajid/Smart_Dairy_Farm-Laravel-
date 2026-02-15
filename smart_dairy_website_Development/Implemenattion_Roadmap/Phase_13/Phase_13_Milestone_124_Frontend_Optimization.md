# Milestone 124: Frontend Optimization

## Smart Dairy Digital Smart Portal + ERP - Phase 13: Performance & AI Optimization

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 124 of 150 (4 of 10 in Phase 13)                                       |
| **Title**        | Frontend Optimization                                                  |
| **Phase**        | Phase 13 - Optimization: Performance & AI                              |
| **Days**         | Days 616-620 (of 750 total)                                            |
| **Duration**     | 5 working days                                                         |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Optimize frontend performance to achieve Lighthouse score >90, page load time <2s, and Time to Interactive (TTI) <3s through code splitting, lazy loading, image optimization, and Core Web Vitals improvements.

### 1.2 Objectives

1. Implement route-based code splitting to reduce initial bundle size by 50%
2. Add lazy loading for all below-fold components and images
3. Optimize images with WebP conversion and responsive sizing
4. Extract and inline critical CSS for above-fold content
5. Implement Service Worker for offline support and caching
6. Add preloading/prefetching for anticipated navigation
7. Optimize React component rendering with memoization
8. Implement virtual scrolling for large data lists
9. Achieve Lighthouse Performance score >90

### 1.3 Key Deliverables

| Deliverable                       | Owner  | Format            | Due Day |
| --------------------------------- | ------ | ----------------- | ------- |
| Code Splitting Configuration      | Dev 3  | Vite config       | 616     |
| Lazy Loading HOCs                 | Dev 3  | React components  | 616     |
| Image Optimization Pipeline       | Dev 2  | Build scripts     | 617     |
| Critical CSS Extraction           | Dev 3  | Build config      | 617     |
| Service Worker Implementation     | Dev 3  | JavaScript        | 618     |
| Preload/Prefetch Strategy         | Dev 3  | React hooks       | 618     |
| Component Memoization             | Dev 3  | React components  | 619     |
| Virtual Scrolling Components      | Dev 3  | React components  | 619     |
| Lighthouse CI Integration         | Dev 2  | CI/CD config      | 620     |
| Frontend Optimization Report      | All    | Markdown/PDF      | 620     |

### 1.4 Success Criteria

- [ ] Lighthouse Performance score >90
- [ ] Initial bundle size reduced by 50%
- [ ] LCP (Largest Contentful Paint) <2.5s
- [ ] FID (First Input Delay) <100ms
- [ ] CLS (Cumulative Layout Shift) <0.1
- [ ] TTI (Time to Interactive) <3s
- [ ] All images served as WebP with fallback
- [ ] Service Worker caching operational

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------------- |
| RFP-PERF-001 | RFP    | Page load time <2 seconds                | 616-620 | All optimizations           |
| SRS-PERF-003 | SRS    | Lighthouse score >90                     | 616-620 | All optimizations           |
| BRD-UX-001   | BRD    | Smooth user experience                   | 619     | Virtual scrolling           |
| IMPL-FE-01   | Guide  | Code splitting standards                 | 616     | Code splitting              |
| IMPL-FE-02   | Guide  | Image optimization requirements          | 617     | Image pipeline              |

---

## 3. Day-by-Day Breakdown

### Day 616 - Code Splitting & Lazy Loading

**Objective:** Implement route-based code splitting and component lazy loading.

#### Dev 3 - Frontend Lead (8h)

**Task 1: Vite Code Splitting Configuration (4h)**

```typescript
// vite.config.ts
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
  plugins: [
    react(),
    visualizer({
      filename: 'dist/bundle-stats.html',
      gzipSize: true,
      brotliSize: true
    })
  ],
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          // Vendor chunks - split large dependencies
          'vendor-react': ['react', 'react-dom', 'react-router-dom'],
          'vendor-ui': ['antd', '@ant-design/icons'],
          'vendor-charts': ['@ant-design/plots', 'echarts', 'echarts-for-react'],
          'vendor-utils': ['lodash-es', 'dayjs', 'axios'],
          'vendor-forms': ['@ant-design/pro-form', 'react-hook-form'],

          // Feature chunks
          'feature-farm': [
            './src/pages/farm/AnimalList',
            './src/pages/farm/AnimalDetail',
            './src/pages/farm/HealthRecords'
          ],
          'feature-orders': [
            './src/pages/orders/OrderList',
            './src/pages/orders/OrderDetail',
            './src/pages/orders/CreateOrder'
          ],
          'feature-analytics': [
            './src/pages/analytics/Dashboard',
            './src/pages/analytics/Reports',
            './src/pages/analytics/Charts'
          ]
        }
      }
    },
    chunkSizeWarningLimit: 500,
    sourcemap: true,
    minify: 'terser',
    terserOptions: {
      compress: {
        drop_console: true,
        drop_debugger: true
      }
    }
  }
});
```

**Task 2: Lazy Loading Implementation (4h)**

```tsx
// frontend/src/routes/LazyRoutes.tsx
import React, { Suspense, lazy, ComponentType } from 'react';
import { Spin } from 'antd';

// Loading fallback component
const PageLoader: React.FC = () => (
  <div className="page-loader">
    <Spin size="large" tip="Loading..." />
  </div>
);

// Error boundary for lazy components
class LazyErrorBoundary extends React.Component<
  { children: React.ReactNode; fallback?: React.ReactNode },
  { hasError: boolean }
> {
  state = { hasError: false };

  static getDerivedStateFromError() {
    return { hasError: true };
  }

  render() {
    if (this.state.hasError) {
      return this.props.fallback || <div>Failed to load component</div>;
    }
    return this.props.children;
  }
}

// HOC for lazy loading with retry
function lazyWithRetry<T extends ComponentType<any>>(
  importFn: () => Promise<{ default: T }>,
  retries = 3,
  interval = 1000
): React.LazyExoticComponent<T> {
  return lazy(async () => {
    for (let i = 0; i < retries; i++) {
      try {
        return await importFn();
      } catch (error) {
        if (i === retries - 1) throw error;
        await new Promise(resolve => setTimeout(resolve, interval));
      }
    }
    throw new Error('Failed to load component');
  });
}

// Lazy loaded pages
export const LazyDashboard = lazyWithRetry(() => import('../pages/Dashboard'));
export const LazyProductList = lazyWithRetry(() => import('../pages/products/ProductList'));
export const LazyProductDetail = lazyWithRetry(() => import('../pages/products/ProductDetail'));
export const LazyOrderList = lazyWithRetry(() => import('../pages/orders/OrderList'));
export const LazyOrderDetail = lazyWithRetry(() => import('../pages/orders/OrderDetail'));
export const LazyAnimalList = lazyWithRetry(() => import('../pages/farm/AnimalList'));
export const LazyAnimalDetail = lazyWithRetry(() => import('../pages/farm/AnimalDetail'));
export const LazyAnalytics = lazyWithRetry(() => import('../pages/analytics/Dashboard'));
export const LazySettings = lazyWithRetry(() => import('../pages/settings/Settings'));

// Wrapper component for lazy routes
export const LazyRoute: React.FC<{
  component: React.LazyExoticComponent<ComponentType<any>>;
  fallback?: React.ReactNode;
}> = ({ component: Component, fallback }) => (
  <LazyErrorBoundary fallback={fallback}>
    <Suspense fallback={<PageLoader />}>
      <Component />
    </Suspense>
  </LazyErrorBoundary>
);

// Route configuration
export const routes = [
  { path: '/', element: <LazyRoute component={LazyDashboard} /> },
  { path: '/products', element: <LazyRoute component={LazyProductList} /> },
  { path: '/products/:id', element: <LazyRoute component={LazyProductDetail} /> },
  { path: '/orders', element: <LazyRoute component={LazyOrderList} /> },
  { path: '/orders/:id', element: <LazyRoute component={LazyOrderDetail} /> },
  { path: '/farm/animals', element: <LazyRoute component={LazyAnimalList} /> },
  { path: '/farm/animals/:id', element: <LazyRoute component={LazyAnimalDetail} /> },
  { path: '/analytics', element: <LazyRoute component={LazyAnalytics} /> },
  { path: '/settings', element: <LazyRoute component={LazySettings} /> },
];
```

#### Dev 2 - Full-Stack Developer (8h)

**Task: Build Pipeline Optimization (8h)**

```javascript
// scripts/build-optimize.js
const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');
const { gzip, brotliCompress } = require('zlib');
const { promisify } = require('util');

const gzipAsync = promisify(gzip);
const brotliAsync = promisify(brotliCompress);

async function optimizeBuild() {
  console.log('🚀 Starting optimized build...\n');

  // Step 1: Build with Vite
  console.log('📦 Building with Vite...');
  execSync('vite build', { stdio: 'inherit' });

  // Step 2: Compress assets
  console.log('\n🗜️ Compressing assets...');
  await compressAssets('./dist');

  // Step 3: Generate bundle report
  console.log('\n📊 Generating bundle report...');
  generateBundleReport('./dist');

  console.log('\n✅ Build optimization complete!');
}

async function compressAssets(distDir) {
  const files = getAllFiles(distDir).filter(f =>
    /\.(js|css|html|json|svg)$/.test(f)
  );

  for (const file of files) {
    const content = fs.readFileSync(file);

    // Gzip
    const gzipped = await gzipAsync(content, { level: 9 });
    fs.writeFileSync(`${file}.gz`, gzipped);

    // Brotli
    const brotlied = await brotliAsync(content);
    fs.writeFileSync(`${file}.br`, brotlied);

    const savings = ((1 - brotlied.length / content.length) * 100).toFixed(1);
    console.log(`  ${path.basename(file)}: ${savings}% smaller (brotli)`);
  }
}

function getAllFiles(dir, files = []) {
  const items = fs.readdirSync(dir);
  for (const item of items) {
    const fullPath = path.join(dir, item);
    if (fs.statSync(fullPath).isDirectory()) {
      getAllFiles(fullPath, files);
    } else {
      files.push(fullPath);
    }
  }
  return files;
}

function generateBundleReport(distDir) {
  const jsFiles = getAllFiles(distDir).filter(f => f.endsWith('.js'));
  const cssFiles = getAllFiles(distDir).filter(f => f.endsWith('.css'));

  const report = {
    totalJsSize: 0,
    totalCssSize: 0,
    chunks: []
  };

  for (const file of jsFiles) {
    const size = fs.statSync(file).size;
    report.totalJsSize += size;
    report.chunks.push({
      name: path.basename(file),
      size,
      sizeFormatted: formatBytes(size)
    });
  }

  for (const file of cssFiles) {
    report.totalCssSize += fs.statSync(file).size;
  }

  report.chunks.sort((a, b) => b.size - a.size);

  console.log('\n📋 Bundle Summary:');
  console.log(`  Total JS: ${formatBytes(report.totalJsSize)}`);
  console.log(`  Total CSS: ${formatBytes(report.totalCssSize)}`);
  console.log('\n  Top chunks:');
  report.chunks.slice(0, 10).forEach(c => {
    console.log(`    ${c.name}: ${c.sizeFormatted}`);
  });

  fs.writeFileSync(
    path.join(distDir, 'bundle-report.json'),
    JSON.stringify(report, null, 2)
  );
}

function formatBytes(bytes) {
  if (bytes < 1024) return bytes + ' B';
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
  return (bytes / 1024 / 1024).toFixed(2) + ' MB';
}

optimizeBuild().catch(console.error);
```

---

### Day 617 - Image Optimization & Critical CSS

**Objective:** Implement image optimization pipeline and critical CSS extraction.

#### Dev 3 - Frontend Lead (8h)

**Task 1: Optimized Image Component (4h)**

```tsx
// frontend/src/components/common/OptimizedImage.tsx
import React, { useState, useRef, useEffect } from 'react';

interface OptimizedImageProps {
  src: string;
  alt: string;
  width?: number;
  height?: number;
  className?: string;
  lazy?: boolean;
  placeholder?: 'blur' | 'empty';
  sizes?: string;
  priority?: boolean;
}

const OptimizedImage: React.FC<OptimizedImageProps> = ({
  src,
  alt,
  width,
  height,
  className,
  lazy = true,
  placeholder = 'blur',
  sizes = '100vw',
  priority = false
}) => {
  const [isLoaded, setIsLoaded] = useState(false);
  const [isInView, setIsInView] = useState(!lazy || priority);
  const imgRef = useRef<HTMLImageElement>(null);

  // Generate srcset for responsive images
  const generateSrcSet = (baseSrc: string): string => {
    const widths = [320, 640, 768, 1024, 1280, 1920];
    const extension = baseSrc.split('.').pop();
    const basePath = baseSrc.replace(`.${extension}`, '');

    return widths
      .map(w => `${basePath}-${w}w.webp ${w}w`)
      .join(', ');
  };

  // Intersection Observer for lazy loading
  useEffect(() => {
    if (!lazy || priority) return;

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsInView(true);
          observer.disconnect();
        }
      },
      { rootMargin: '200px' }
    );

    if (imgRef.current) {
      observer.observe(imgRef.current);
    }

    return () => observer.disconnect();
  }, [lazy, priority]);

  // Generate blur placeholder
  const blurDataUrl = `data:image/svg+xml;base64,${btoa(
    `<svg xmlns="http://www.w3.org/2000/svg" width="${width || 100}" height="${height || 100}">
      <filter id="blur" x="0" y="0">
        <feGaussianBlur stdDeviation="20"/>
      </filter>
      <rect width="100%" height="100%" fill="#f0f0f0" filter="url(#blur)"/>
    </svg>`
  )}`;

  return (
    <picture ref={imgRef} className={`optimized-image ${className || ''}`}>
      {/* WebP source */}
      {isInView && (
        <>
          <source
            type="image/webp"
            srcSet={generateSrcSet(src)}
            sizes={sizes}
          />
          {/* Fallback source */}
          <source
            type={`image/${src.split('.').pop()}`}
            srcSet={src}
          />
        </>
      )}

      <img
        src={isInView ? src : (placeholder === 'blur' ? blurDataUrl : undefined)}
        alt={alt}
        width={width}
        height={height}
        loading={lazy && !priority ? 'lazy' : 'eager'}
        decoding="async"
        onLoad={() => setIsLoaded(true)}
        style={{
          opacity: isLoaded ? 1 : 0.5,
          transition: 'opacity 0.3s ease-in-out'
        }}
      />
    </picture>
  );
};

export default OptimizedImage;
```

**Task 2: Critical CSS Configuration (4h)**

```javascript
// vite.config.ts - Critical CSS plugin
import { Plugin } from 'vite';
import critical from 'critical';
import { JSDOM } from 'jsdom';

function criticalCssPlugin(): Plugin {
  return {
    name: 'critical-css',
    apply: 'build',
    async closeBundle() {
      console.log('Extracting critical CSS...');

      const pages = [
        { url: '/', output: 'index.html' },
        { url: '/products', output: 'products.html' },
        { url: '/dashboard', output: 'dashboard.html' }
      ];

      for (const page of pages) {
        try {
          await critical.generate({
            base: 'dist/',
            src: page.output,
            target: page.output,
            inline: true,
            width: 1300,
            height: 900,
            penthouse: {
              blockJSRequests: false
            }
          });
          console.log(`  ✓ Critical CSS extracted for ${page.url}`);
        } catch (error) {
          console.warn(`  ⚠ Failed for ${page.url}:`, error.message);
        }
      }
    }
  };
}
```

---

### Day 618 - Service Worker & Prefetching

**Objective:** Implement Service Worker for caching and add prefetching strategies.

#### Dev 3 - Frontend Lead (8h)

**Task 1: Service Worker Implementation (4h)**

```typescript
// frontend/src/service-worker.ts
/// <reference lib="webworker" />

import { precacheAndRoute, cleanupOutdatedCaches } from 'workbox-precaching';
import { registerRoute } from 'workbox-routing';
import { CacheFirst, NetworkFirst, StaleWhileRevalidate } from 'workbox-strategies';
import { ExpirationPlugin } from 'workbox-expiration';
import { CacheableResponsePlugin } from 'workbox-cacheable-response';

declare const self: ServiceWorkerGlobalScope;

// Precache static assets
precacheAndRoute(self.__WB_MANIFEST);
cleanupOutdatedCaches();

// Cache API responses
registerRoute(
  ({ url }) => url.pathname.startsWith('/api/'),
  new NetworkFirst({
    cacheName: 'api-cache',
    plugins: [
      new CacheableResponsePlugin({ statuses: [0, 200] }),
      new ExpirationPlugin({
        maxEntries: 100,
        maxAgeSeconds: 60 * 5 // 5 minutes
      })
    ]
  })
);

// Cache images
registerRoute(
  ({ request }) => request.destination === 'image',
  new CacheFirst({
    cacheName: 'images-cache',
    plugins: [
      new CacheableResponsePlugin({ statuses: [0, 200] }),
      new ExpirationPlugin({
        maxEntries: 200,
        maxAgeSeconds: 60 * 60 * 24 * 30 // 30 days
      })
    ]
  })
);

// Cache fonts
registerRoute(
  ({ request }) => request.destination === 'font',
  new CacheFirst({
    cacheName: 'fonts-cache',
    plugins: [
      new CacheableResponsePlugin({ statuses: [0, 200] }),
      new ExpirationPlugin({
        maxEntries: 30,
        maxAgeSeconds: 60 * 60 * 24 * 365 // 1 year
      })
    ]
  })
);

// Cache page navigations
registerRoute(
  ({ request }) => request.mode === 'navigate',
  new NetworkFirst({
    cacheName: 'pages-cache',
    plugins: [
      new CacheableResponsePlugin({ statuses: [0, 200] }),
      new ExpirationPlugin({
        maxEntries: 50,
        maxAgeSeconds: 60 * 60 * 24 // 1 day
      })
    ]
  })
);

// Handle offline fallback
self.addEventListener('fetch', (event) => {
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request).catch(() => caches.match('/offline.html'))
    );
  }
});
```

**Task 2: Prefetching Hook (4h)**

```tsx
// frontend/src/hooks/usePrefetch.ts
import { useCallback, useEffect } from 'react';
import { useLocation } from 'react-router-dom';

// Route prefetch map
const routePrefetchMap: Record<string, string[]> = {
  '/': ['/products', '/dashboard', '/orders'],
  '/products': ['/products/:id', '/cart'],
  '/dashboard': ['/analytics', '/reports'],
  '/orders': ['/orders/:id', '/checkout']
};

// Prefetch component chunks
const prefetchComponent = (path: string): void => {
  const componentMap: Record<string, () => Promise<any>> = {
    '/products': () => import('../pages/products/ProductList'),
    '/products/:id': () => import('../pages/products/ProductDetail'),
    '/orders': () => import('../pages/orders/OrderList'),
    '/orders/:id': () => import('../pages/orders/OrderDetail'),
    '/dashboard': () => import('../pages/Dashboard'),
    '/analytics': () => import('../pages/analytics/Dashboard'),
    '/cart': () => import('../pages/cart/Cart'),
    '/checkout': () => import('../pages/checkout/Checkout')
  };

  if (componentMap[path]) {
    componentMap[path]().catch(() => {
      // Silently fail - prefetch is best effort
    });
  }
};

// Prefetch API data
const prefetchData = async (path: string): Promise<void> => {
  const dataMap: Record<string, string> = {
    '/products': '/api/v1/products?limit=20',
    '/dashboard': '/api/v1/dashboard/summary',
    '/orders': '/api/v1/orders?limit=20'
  };

  if (dataMap[path]) {
    try {
      await fetch(dataMap[path], {
        method: 'GET',
        credentials: 'include'
      });
    } catch {
      // Silently fail
    }
  }
};

export function usePrefetch(): void {
  const location = useLocation();

  useEffect(() => {
    const routesToPrefetch = routePrefetchMap[location.pathname] || [];

    // Use requestIdleCallback for non-critical prefetching
    if ('requestIdleCallback' in window) {
      window.requestIdleCallback(() => {
        routesToPrefetch.forEach(route => {
          prefetchComponent(route);
          prefetchData(route);
        });
      }, { timeout: 2000 });
    } else {
      // Fallback with setTimeout
      setTimeout(() => {
        routesToPrefetch.forEach(route => {
          prefetchComponent(route);
          prefetchData(route);
        });
      }, 1000);
    }
  }, [location.pathname]);
}

// Link component with prefetch on hover
export const PrefetchLink: React.FC<{
  to: string;
  children: React.ReactNode;
  className?: string;
}> = ({ to, children, className }) => {
  const handleMouseEnter = useCallback(() => {
    prefetchComponent(to);
    prefetchData(to);
  }, [to]);

  return (
    <a
      href={to}
      className={className}
      onMouseEnter={handleMouseEnter}
      onFocus={handleMouseEnter}
    >
      {children}
    </a>
  );
};
```

---

### Day 619 - Component Optimization & Virtual Scrolling

**Objective:** Optimize React component rendering and implement virtual scrolling.

#### Dev 3 - Frontend Lead (8h)

**Task 1: Memoization Utilities (4h)**

```tsx
// frontend/src/utils/memoization.ts
import React, { memo, useMemo, useCallback, useRef, useEffect } from 'react';
import isEqual from 'lodash-es/isEqual';

// Deep comparison memo HOC
export function deepMemo<P extends object>(
  Component: React.ComponentType<P>,
  propsAreEqual?: (prevProps: P, nextProps: P) => boolean
): React.MemoExoticComponent<React.ComponentType<P>> {
  return memo(Component, propsAreEqual || isEqual);
}

// Hook for expensive computations with comparison
export function useDeepMemo<T>(factory: () => T, deps: any[]): T {
  const ref = useRef<{ deps: any[]; value: T }>();

  if (!ref.current || !isEqual(deps, ref.current.deps)) {
    ref.current = { deps, value: factory() };
  }

  return ref.current.value;
}

// Hook for stable callbacks
export function useStableCallback<T extends (...args: any[]) => any>(
  callback: T
): T {
  const callbackRef = useRef(callback);

  useEffect(() => {
    callbackRef.current = callback;
  });

  return useCallback(
    ((...args) => callbackRef.current(...args)) as T,
    []
  );
}

// Component that prevents unnecessary re-renders
export const RenderOnce: React.FC<{ children: React.ReactNode }> = memo(
  ({ children }) => <>{children}</>,
  () => true
);
```

**Task 2: Virtual Scrolling Component (4h)**

```tsx
// frontend/src/components/common/VirtualList.tsx
import React, { useRef, useState, useEffect, useCallback } from 'react';

interface VirtualListProps<T> {
  items: T[];
  itemHeight: number;
  renderItem: (item: T, index: number) => React.ReactNode;
  overscan?: number;
  className?: string;
  onEndReached?: () => void;
  endReachedThreshold?: number;
}

function VirtualList<T>({
  items,
  itemHeight,
  renderItem,
  overscan = 5,
  className,
  onEndReached,
  endReachedThreshold = 200
}: VirtualListProps<T>): React.ReactElement {
  const containerRef = useRef<HTMLDivElement>(null);
  const [scrollTop, setScrollTop] = useState(0);
  const [containerHeight, setContainerHeight] = useState(0);

  // Calculate visible range
  const startIndex = Math.max(0, Math.floor(scrollTop / itemHeight) - overscan);
  const endIndex = Math.min(
    items.length,
    Math.ceil((scrollTop + containerHeight) / itemHeight) + overscan
  );

  const visibleItems = items.slice(startIndex, endIndex);
  const totalHeight = items.length * itemHeight;
  const offsetY = startIndex * itemHeight;

  // Handle scroll
  const handleScroll = useCallback((e: React.UIEvent<HTMLDivElement>) => {
    const target = e.currentTarget;
    setScrollTop(target.scrollTop);

    // Check if near end
    if (onEndReached) {
      const distanceFromEnd = totalHeight - target.scrollTop - target.clientHeight;
      if (distanceFromEnd < endReachedThreshold) {
        onEndReached();
      }
    }
  }, [totalHeight, onEndReached, endReachedThreshold]);

  // Measure container
  useEffect(() => {
    if (containerRef.current) {
      const resizeObserver = new ResizeObserver((entries) => {
        setContainerHeight(entries[0].contentRect.height);
      });
      resizeObserver.observe(containerRef.current);
      return () => resizeObserver.disconnect();
    }
  }, []);

  return (
    <div
      ref={containerRef}
      className={`virtual-list ${className || ''}`}
      style={{ overflow: 'auto', height: '100%' }}
      onScroll={handleScroll}
    >
      <div style={{ height: totalHeight, position: 'relative' }}>
        <div
          style={{
            position: 'absolute',
            top: offsetY,
            left: 0,
            right: 0
          }}
        >
          {visibleItems.map((item, index) => (
            <div
              key={startIndex + index}
              style={{ height: itemHeight }}
            >
              {renderItem(item, startIndex + index)}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

export default VirtualList;
```

---

### Day 620 - Lighthouse CI & Validation

**Objective:** Set up Lighthouse CI and validate all frontend optimizations.

#### Dev 2 - Full-Stack Developer (8h)

**Task: Lighthouse CI Integration (8h)**

```yaml
# .github/workflows/lighthouse-ci.yml
name: Lighthouse CI

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main]

jobs:
  lighthouse:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '20'
          cache: 'npm'

      - name: Install dependencies
        run: npm ci

      - name: Build
        run: npm run build

      - name: Run Lighthouse CI
        uses: treosh/lighthouse-ci-action@v10
        with:
          configPath: './lighthouserc.json'
          uploadArtifacts: true
          temporaryPublicStorage: true

      - name: Assert Lighthouse scores
        run: |
          npm install -g @lhci/cli
          lhci assert --config=lighthouserc.json
```

---

## 4. Technical Specifications

### 4.1 Bundle Size Targets

| Chunk | Max Size (gzip) |
|-------|-----------------|
| Main | 50KB |
| Vendor React | 40KB |
| Vendor UI | 100KB |
| Feature chunks | 30KB each |

### 4.2 Core Web Vitals Targets

| Metric | Target | Priority |
|--------|--------|----------|
| LCP | <2.5s | High |
| FID | <100ms | High |
| CLS | <0.1 | High |
| TTI | <3s | Medium |
| TBT | <300ms | Medium |

---

## 5. Success Criteria Validation

- [ ] Lighthouse Performance score >90
- [ ] Initial JS bundle <200KB (gzip)
- [ ] All Core Web Vitals in green
- [ ] Images loading as WebP
- [ ] Service Worker registered and caching
- [ ] Code splitting working correctly

---

**End of Milestone 124 Document**
