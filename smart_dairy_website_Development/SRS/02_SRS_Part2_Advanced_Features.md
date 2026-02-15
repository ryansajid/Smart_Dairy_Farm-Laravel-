# SOFTWARE REQUIREMENTS SPECIFICATION (SRS)
## Smart Dairy Limited - Website Development Project
### Part 2: Advanced Features & Integrations

**Document Version:** 1.0  
**Date:** December 2, 2025  
**Continued from:** SRS Part 1

---

## 4. B2B PORTAL FEATURES

### 4.1 B2B Account Registration

**Priority:** High | **Phase:** 2 | **Effort:** 5 days

**Database Schema:**
```sql
CREATE TABLE b2b_applications (
  id SERIAL PRIMARY KEY,
  -- Company Information
  company_name VARCHAR(255) NOT NULL,
  business_registration_number VARCHAR(100) NOT NULL,
  trade_license_document VARCHAR(255),  -- file path
  business_type VARCHAR(50) NOT NULL,
  annual_purchase_estimate VARCHAR(50),
  number_of_locations INTEGER,
  
  -- Contact Information
  contact_person_name VARCHAR(100) NOT NULL,
  contact_person_position VARCHAR(100),
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  
  -- Address
  company_address TEXT NOT NULL,
  city VARCHAR(100),
  postal_code VARCHAR(20),
  
  -- Billing & Tax
  billing_address TEXT,
  vat_registration_number VARCHAR(100),
  
  -- Bank Details
  bank_name VARCHAR(100),
  bank_account_number VARCHAR(50),
  bank_account_name VARCHAR(100),
  
  -- Application Status
  status VARCHAR(50) DEFAULT 'pending',
  -- Status: pending, under_review, approved, rejected
  reviewed_by INTEGER REFERENCES users(id),
  reviewed_at TIMESTAMP,
  rejection_reason TEXT,
  admin_notes TEXT,
  
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE b2b_customers (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id) UNIQUE,
  application_id INTEGER REFERENCES b2b_applications(id),
  company_name VARCHAR(255) NOT NULL,
  
  -- Account Manager
  account_manager_id INTEGER REFERENCES users(id),
  
  -- Credit Terms
  credit_limit DECIMAL(12, 2) DEFAULT 0,
  payment_terms VARCHAR(50),  -- 'NET30', 'NET60', 'prepaid'
  credit_used DECIMAL(12, 2) DEFAULT 0,
  
  -- Pricing Tier
  pricing_tier VARCHAR(50) DEFAULT 'standard',
  -- Tiers: standard, silver, gold, platinum
  discount_percentage DECIMAL(5, 2) DEFAULT 0,
  
  -- Status
  status VARCHAR(50) DEFAULT 'active',
  -- Status: active, suspended, inactive
  
  -- Preferences
  delivery_schedule_preference VARCHAR(100),
  preferred_payment_method VARCHAR(50),
  
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE b2b_delivery_locations (
  id SERIAL PRIMARY KEY,
  b2b_customer_id INTEGER REFERENCES b2b_customers(id),
  location_name VARCHAR(100) NOT NULL,  -- e.g., "Main Restaurant", "Branch 2"
  contact_person VARCHAR(100),
  contact_phone VARCHAR(20),
  address TEXT NOT NULL,
  city VARCHAR(100),
  area VARCHAR(100),
  postal_code VARCHAR(20),
  landmark VARCHAR(255),
  special_instructions TEXT,
  is_default BOOLEAN DEFAULT FALSE,
  active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NOW()
);
```

**Application Flow:**

1. **Submit Application:**
```
POST /api/b2b/apply
Body: {
  companyName, businessRegistrationNumber,
  businessType, annualPurchaseEstimate,
  contactPersonName, contactPersonPosition,
  email, phone,
  companyAddress, city, postalCode,
  vatRegistrationNumber,
  bankName, bankAccountNumber, bankAccountName
}
Files: tradeLicense (PDF/JPG)
Response: { applicationId, status: 'pending', message }
```

2. **Admin Reviews Application:**
   - View application details
   - Download/view trade license
   - Verify business registration number
   - Assign account manager
   - Set credit limit and payment terms
   - Approve or reject with reason

3. **Approval Actions:**
```
PATCH /api/admin/b2b-applications/:id/review
Body: {
  action: 'approve' | 'reject',
  rejectionReason?,
  accountManagerId?,
  creditLimit?,
  paymentTerms?,
  pricingTier?
}
Response: { application, message }
```

4. **Post-Approval:**
   - Create user account (if doesn't exist)
   - Create b2b_customer record
   - Send welcome email with credentials
   - Send account manager introduction email

**Acceptance Criteria:**
- ✓ B2B application form submits with file upload
- ✓ Admin can review applications
- ✓ Approval creates B2B customer account
- ✓ Rejection sends notification with reason
- ✓ Account manager assigned to approved customers

---

### 4.2 B2B Customer Dashboard

**Priority:** High | **Phase:** 2 | **Effort:** 4 days

**Dashboard Components:**

```typescript
interface B2BDashboard {
  accountInfo: {
    companyName: string;
    accountManager: {
      name: string;
      email: string;
      phone: string;
    };
    creditLimit: number;
    creditUsed: number;
    creditAvailable: number;
    paymentTerms: string;
    pricingTier: string;
  };
  
  quickStats: {
    outstandingInvoices: number;
    outstandingAmount: number;
    monthlySpend: number;
    lastOrderDate: string;
  };
  
  recentOrders: Order[];
  favoriteProducts: Product[];
  upcomingDeliveries: Delivery[];
  announcements: Announcement[];
}
```

**API:**
```
GET /api/b2b/dashboard
Response: B2BDashboard
```

**UI Components:**
- Account overview card (credit, account manager)
- Quick actions buttons:
  - New Order
  - Reorder Last
  - Request Quote
  - View Invoices
  - Download Contract
- Charts:
  - Monthly spend trend
  - Top ordered products
- Recent orders table (last 10)
- Outstanding invoices alert (if any)

---

### 4.3 B2B Bulk Ordering

**Priority:** High | **Phase:** 2 | **Effort:** 6 days

**Ordering Methods:**

**1. Quick Order (SKU Entry):**
```typescript
interface QuickOrderLine {
  sku: string;
  quantity: number;
}

// Frontend UI
<form>
  <textarea
    placeholder="SKU, Quantity (one per line)
Example:
MILK-500ML, 100
YOGURT-1L, 50"
  />
  <button>Add to Order</button>
</form>
```

**API:**
```
POST /api/b2b/orders/quick-validate
Body: { lines: QuickOrderLine[] }
Response: {
  validProducts: {
    sku, productName, price, quantity, available
  }[],
  errors: {
    sku, error: 'Not found' | 'Out of stock' | 'Quantity exceeds limit'
  }[]
}
```

**2. CSV Upload:**
```
CSV Format:
SKU,Quantity,Delivery Location
MILK-500ML,100,Main Branch
YOGURT-1L,50,Main Branch
CHEESE-200G,25,Branch 2
```

**API:**
```
POST /api/b2b/orders/csv-upload
Body: FormData with file
Response: {
  validRows: ParsedRow[],
  invalidRows: {
    row, errors
  }[],
  summary: {
    totalRows, validRows, invalidRows
  }
}
```

**3. Saved Order Templates:**
```sql
CREATE TABLE b2b_order_templates (
  id SERIAL PRIMARY KEY,
  b2b_customer_id INTEGER REFERENCES b2b_customers(id),
  template_name VARCHAR(100) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE b2b_order_template_items (
  id SERIAL PRIMARY KEY,
  template_id INTEGER REFERENCES b2b_order_templates(id) ON DELETE CASCADE,
  product_id INTEGER REFERENCES products(id),
  variant_id INTEGER REFERENCES product_variants(id),
  quantity INTEGER NOT NULL,
  delivery_location_id INTEGER REFERENCES b2b_delivery_locations(id)
);
```

**API:**
```
POST /api/b2b/order-templates
Body: { name, description, items[] }
Response: { template }

GET /api/b2b/order-templates
Response: { templates[] }

POST /api/b2b/orders/from-template/:templateId
Response: { orderDraft with items }
```

**4. Standing Orders (Recurring):**
```sql
CREATE TABLE b2b_standing_orders (
  id SERIAL PRIMARY KEY,
  b2b_customer_id INTEGER REFERENCES b2b_customers(id),
  name VARCHAR(100) NOT NULL,
  frequency VARCHAR(50) NOT NULL,  -- 'daily', 'weekly', 'biweekly', 'monthly'
  delivery_day VARCHAR(20),  -- 'monday', 'tuesday', etc. for weekly
  delivery_date INTEGER,     -- day of month for monthly
  next_order_date DATE NOT NULL,
  end_date DATE,            -- null for ongoing
  status VARCHAR(50) DEFAULT 'active',
  -- Status: active, paused, completed
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE b2b_standing_order_items (
  id SERIAL PRIMARY KEY,
  standing_order_id INTEGER REFERENCES b2b_standing_orders(id) ON DELETE CASCADE,
  product_id INTEGER REFERENCES products(id),
  variant_id INTEGER REFERENCES product_variants(id),
  quantity INTEGER NOT NULL,
  delivery_location_id INTEGER REFERENCES b2b_delivery_locations(id)
);
```

**Cron Job (Daily):**
```javascript
// Check for standing orders due today
async function processStandingOrders() {
  const dueOrders = await db.query(`
    SELECT * FROM b2b_standing_orders
    WHERE status = 'active'
    AND next_order_date <= CURRENT_DATE
  `);
  
  for (const standingOrder of dueOrders) {
    // Create order from standing order
    const order = await createB2BOrderFromStandingOrder(standingOrder);
    
    // Send notification to customer
    await sendEmail({
      to: standingOrder.customer.email,
      subject: 'Your Standing Order is Being Processed',
      body: `Order #${order.order_number} has been generated...`
    });
    
    // Calculate next order date
    const nextDate = calculateNextOrderDate(
      standingOrder.frequency,
      standingOrder.next_order_date
    );
    
    // Update standing order
    await db.query(`
      UPDATE b2b_standing_orders
      SET next_order_date = $1
      WHERE id = $2
    `, [nextDate, standingOrder.id]);
  }
}

// Run daily at 6 AM
cron.schedule('0 6 * * *', processStandingOrders);
```

**B2B Checkout:**
```typescript
interface B2BCheckout {
  items: OrderItem[];
  deliveryLocation: DeliveryLocation;
  deliveryDate: Date;
  deliveryTimeSlot: string;
  poNumber?: string;  // Customer's Purchase Order number
  specialInstructions?: string;
  paymentMethod: 'credit' | 'bank_transfer' | 'online';
}
```

**Approval Workflow (for large orders):**
```sql
CREATE TABLE b2b_order_approvals (
  id SERIAL PRIMARY KEY,
  order_id INTEGER REFERENCES orders(id),
  submitted_by INTEGER REFERENCES users(id),
  approver_id INTEGER REFERENCES users(id),
  status VARCHAR(50) DEFAULT 'pending',
  -- Status: pending, approved, rejected
  approved_at TIMESTAMP,
  rejected_at TIMESTAMP,
  rejection_reason TEXT,
  created_at TIMESTAMP DEFAULT NOW()
);
```

**Approval Rules:**
```javascript
function requiresApproval(order, b2bCustomer) {
  // Rule 1: Order amount exceeds threshold
  if (order.total_amount > b2bCustomer.approval_threshold) {
    return true;
  }
  
  // Rule 2: First order from this user
  if (order.user.is_first_order) {
    return true;
  }
  
  // Rule 3: Custom rule per customer
  if (b2bCustomer.requires_approval_for_all) {
    return true;
  }
  
  return false;
}
```

**Acceptance Criteria:**
- ✓ Quick order (SKU + quantity) works
- ✓ CSV upload parses and validates correctly
- ✓ Order templates can be saved and loaded
- ✓ Standing orders auto-generate on schedule
- ✓ Approval workflow routes to designated approver
- ✓ B2B pricing applies correctly
- ✓ Multiple delivery locations supported

---

### 4.4 Quote Request System

**Priority:** Medium | **Phase:** 2 | **Effort:** 4 days

**Database Schema:**
```sql
CREATE TABLE b2b_quote_requests (
  id SERIAL PRIMARY KEY,
  quote_number VARCHAR(50) UNIQUE NOT NULL,  -- QR-2025-001
  b2b_customer_id INTEGER REFERENCES b2b_customers(id),
  requested_by INTEGER REFERENCES users(id),
  
  -- Request Details
  title VARCHAR(255) NOT NULL,
  description TEXT,
  required_by_date DATE,
  delivery_frequency VARCHAR(50),
  contract_duration VARCHAR(50),
  
  -- Status
  status VARCHAR(50) DEFAULT 'submitted',
  -- Status: submitted, under_review, quoted, accepted, declined, expired
  
  -- Quote Details (filled by admin)
  quoted_by INTEGER REFERENCES users(id),
  quoted_at TIMESTAMP,
  quote_valid_until DATE,
  total_amount DECIMAL(12, 2),
  payment_terms VARCHAR(50),
  delivery_terms TEXT,
  additional_terms TEXT,
  
  -- Response
  customer_response VARCHAR(50),
  -- Response: accepted, declined, negotiating
  customer_notes TEXT,
  responded_at TIMESTAMP,
  
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE b2b_quote_request_items (
  id SERIAL PRIMARY KEY,
  quote_request_id INTEGER REFERENCES b2b_quote_requests(id) ON DELETE CASCADE,
  product_id INTEGER REFERENCES products(id),
  variant_id INTEGER REFERENCES product_variants(id),
  product_name VARCHAR(255),  -- for custom products
  quantity INTEGER NOT NULL,
  unit VARCHAR(50),
  specifications TEXT,
  
  -- Quote pricing (filled by admin)
  unit_price DECIMAL(10, 2),
  total_price DECIMAL(12, 2),
  discount_percentage DECIMAL(5, 2),
  
  created_at TIMESTAMP DEFAULT NOW()
);
```

**Customer Submits Quote Request:**
```
POST /api/b2b/quote-requests
Body: {
  title, description,
  items: [
    { productId, variantId, quantity, specifications }
  ],
  requiredByDate,
  deliveryFrequency,
  contractDuration
}
Response: { quoteRequestId, quoteNumber }
```

**Admin Prepares Quote:**
```
PATCH /api/admin/b2b/quote-requests/:id/quote
Body: {
  items: [
    { itemId, unitPrice, discountPercentage }
  ],
  paymentTerms,
  deliveryTerms,
  additionalTerms,
  validUntil
}
Response: { quoteRequest }
```

**Customer Reviews Quote:**
- Email notification sent with quote PDF
- Customer logs in to B2B portal
- Views quote details
- Options: Accept, Decline, Request Revision

**Accept Quote:**
```
POST /api/b2b/quote-requests/:id/accept
Response: { 
  message, 
  orderDraftId  // Can convert to order
}
```

**Acceptance Criteria:**
- ✓ Customer can submit quote request with items
- ✓ Admin receives notification
- ✓ Admin can prepare and send quote
- ✓ Quote PDF generated and emailed
- ✓ Customer can accept/decline quote
- ✓ Accepted quote converts to order
- ✓ Quote expires after validity date

---

## 5. VIRTUAL FARM TOUR

**Priority:** High | **Phase:** 2 | **Effort:** 7 days

### 5.1 360° Panoramic Tour

**Technology:** Three.js or Pannellum library

**Implementation:**

**1. Prepare Panoramic Images:**
```bash
# Use 360° camera or stitch images
# Convert to equirectangular format
# Recommended resolution: 8192x4096 pixels
# Compress for web (JPEG quality 85, progressive)

# Example using ImageMagick for compression
convert input.jpg -quality 85 -interlace Plane output.jpg
```

**2. Tour Structure:**
```typescript
interface TourScene {
  id: string;
  name: string;
  imageUrl: string;
  description: string;
  audioUrl?: string;  // narration
  hotspots: Hotspot[];
  initialView: {
    yaw: number;    // horizontal angle
    pitch: number;  // vertical angle
    fov: number;    // field of view
  };
}

interface Hotspot {
  id: string;
  type: 'info' | 'navigation' | 'video' | 'image';
  position: {
    yaw: number;
    pitch: number;
  };
  title: string;
  content?: string;  // for info type
  targetSceneId?: string;  // for navigation type
  mediaUrl?: string;  // for video/image type
}

const tourData: TourScene[] = [
  {
    id: 'entrance',
    name: 'Farm Entrance',
    imageUrl: '/tour/entrance.jpg',
    description: 'Welcome to Smart Dairy Farm',
    audioUrl: '/tour/audio/entrance-en.mp3',
    initialView: { yaw: 0, pitch: 0, fov: 90 },
    hotspots: [
      {
        id: 'info-1',
        type: 'info',
        position: { yaw: 45, pitch: -10 },
        title: 'Our Mission',
        content: 'Providing fresh, quality dairy...'
      },
      {
        id: 'nav-barn',
        type: 'navigation',
        position: { yaw: 90, pitch: 0 },
        title: 'Go to Cow Housing',
        targetSceneId: 'cow-housing'
      }
    ]
  },
  {
    id: 'cow-housing',
    name: 'Cow Housing Facility',
    imageUrl: '/tour/cow-housing.jpg',
    description: 'State-of-the-art cow comfort',
    audioUrl: '/tour/audio/cow-housing-en.mp3',
    initialView: { yaw: 0, pitch: 0, fov: 90 },
    hotspots: [
      {
        id: 'video-1',
        type: 'video',
        position: { yaw: 0, pitch: -20 },
        title: 'Watch: How We Monitor Cow Health',
        mediaUrl: '/tour/videos/cow-health.mp4'
      },
      {
        id: 'nav-milking',
        type: 'navigation',
        position: { yaw: 180, pitch: 0 },
        title: 'Go to Milking Parlor',
        targetSceneId: 'milking-parlor'
      }
    ]
  }
  // ... more scenes
];
```

**3. Implementation with Pannellum:**
```html
<!-- Include Pannellum library -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
<script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>

<div id="panorama" style="width: 100%; height: 600px;"></div>
```

```javascript
// Initialize Pannellum
const viewer = pannellum.viewer('panorama', {
  type: 'equirectangular',
  panorama: tourData[0].imageUrl,
  autoLoad: true,
  hotSpots: tourData[0].hotspots.map(hotspot => ({
    pitch: hotspot.position.pitch,
    yaw: hotspot.position.yaw,
    type: hotspot.type === 'navigation' ? 'scene' : 'info',
    text: hotspot.title,
    sceneId: hotspot.targetSceneId,
    clickHandlerFunc: () => handleHotspotClick(hotspot),
    clickHandlerArgs: hotspot
  })),
  compass: true,
  autoRotate: -2,  // auto-rotate slowly
  autoRotateInactivityDelay: 3000,
  showControls: true
});

function handleHotspotClick(hotspot) {
  if (hotspot.type === 'info') {
    showInfoModal(hotspot.title, hotspot.content);
  } else if (hotspot.type === 'navigation') {
    loadScene(hotspot.targetSceneId);
  } else if (hotspot.type === 'video') {
    showVideoModal(hotspot.mediaUrl);
  }
}

function loadScene(sceneId) {
  const scene = tourData.find(s => s.id === sceneId);
  if (scene) {
    viewer.loadScene(scene.id, {
      type: 'equirectangular',
      panorama: scene.imageUrl,
      hotSpots: scene.hotspots.map(/* ... */)
    });
  }
}
```

**4. Navigation Menu:**
```tsx
// React Component
function VirtualTourNavigator({ scenes, currentScene, onSceneChange }) {
  return (
    <div className="tour-navigator">
      <h3>Tour Locations</h3>
      <ul>
        {scenes.map(scene => (
          <li key={scene.id}>
            <button
              className={scene.id === currentScene.id ? 'active' : ''}
              onClick={() => onSceneChange(scene.id)}
            >
              {scene.name}
            </button>
          </li>
        ))}
      </ul>
    </div>
  );
}
```

**5. Audio Narration:**
```javascript
let audioPlayer = null;

function playAudioNarration(audioUrl) {
  if (audioPlayer) {
    audioPlayer.pause();
  }
  
  audioPlayer = new Audio(audioUrl);
  audioPlayer.play();
}

// Play narration when scene loads
viewer.on('scenechange', (sceneId) => {
  const scene = tourData.find(s => s.id === sceneId);
  if (scene && scene.audioUrl) {
    playAudioNarration(scene.audioUrl);
  }
});

// Audio controls UI
<div className="audio-controls">
  <button onClick={playAudioNarration}>🔊 Play Narration</button>
  <button onClick={() => audioPlayer?.pause()}>⏸ Pause</button>
</div>
```

**6. Mobile Gyroscope Support:**
```javascript
// Enable device orientation for mobile
if (window.DeviceOrientationEvent) {
  viewer.setHfov(90);  // field of view for mobile
  
  // Use Pannellum's built-in orientation support
  pannellum.viewer('panorama', {
    // ... other config
    orientationOnByDefault: true
  });
}
```

**7. VR Mode:**
```javascript
// Enable VR mode
viewer.toggleVR();

// Add VR button
<button onClick={() => viewer.toggleVR()}>
  👓 View in VR
</button>
```

**Database for Tour Data:**
```sql
CREATE TABLE virtual_tour_scenes (
  id SERIAL PRIMARY KEY,
  scene_id VARCHAR(50) UNIQUE NOT NULL,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  image_url VARCHAR(255) NOT NULL,
  audio_url_en VARCHAR(255),
  audio_url_bn VARCHAR(255),
  initial_yaw DECIMAL(6, 2) DEFAULT 0,
  initial_pitch DECIMAL(6, 2) DEFAULT 0,
  initial_fov DECIMAL(5, 2) DEFAULT 90,
  display_order INTEGER DEFAULT 0,
  active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE virtual_tour_hotspots (
  id SERIAL PRIMARY KEY,
  scene_id INTEGER REFERENCES virtual_tour_scenes(id) ON DELETE CASCADE,
  hotspot_type VARCHAR(50) NOT NULL,
  position_yaw DECIMAL(6, 2) NOT NULL,
  position_pitch DECIMAL(6, 2) NOT NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT,
  target_scene_id INTEGER REFERENCES virtual_tour_scenes(id),
  media_url VARCHAR(255),
  display_order INTEGER DEFAULT 0,
  created_at TIMESTAMP DEFAULT NOW()
);
```

**Admin Panel for Tour Management:**
- Upload panoramic images
- Create/edit scenes
- Add/edit hotspots (with position picker)
- Upload audio narration
- Preview tour
- Activate/deactivate scenes

**Acceptance Criteria:**
- ✓ 360° panorama loads and displays smoothly
- ✓ User can navigate by dragging or clicking hotspots
- ✓ Hotspots show information overlays
- ✓ Navigation between scenes works
- ✓ Audio narration plays (with controls)
- ✓ Mobile gyroscope controls work
- ✓ VR mode functions with compatible devices
- ✓ Tour is responsive on all devices

---

### 5.2 Live Farm Camera Feeds

**Priority:** Medium | **Phase:** 2 | **Effort:** 3 days

**Technology:** HLS (HTTP Live Streaming) or WebRTC

**Implementation:**

**1. Setup IP Cameras:**
- Install IP cameras at key farm locations
- Configure cameras for streaming
- Ensure stable network connection
- Use RTSP protocol from cameras

**2. Streaming Server (Simplified for Solo Dev):**

**Option A: Use FFmpeg to convert RTSP to HLS:**
```bash
# Install FFmpeg
sudo apt install ffmpeg

# Stream from RTSP camera to HLS
ffmpeg -rtsp_transport tcp \
  -i rtsp://camera-ip:554/stream \
  -c:v libx264 -preset veryfast -tune zerolatency \
  -c:a aac -b:a 128k \
  -f hls \
  -hls_time 2 \
  -hls_list_size 5 \
  -hls_flags delete_segments \
  /var/www/streams/camera1/stream.m3u8
```

**Option B: Use Node-Media-Server:**
```bash
npm install node-media-server
```

```javascript
// server.js
const NodeMediaServer = require('node-media-server');

const config = {
  rtmp: {
    port: 1935,
    chunk_size: 60000,
    gop_cache: true,
    ping: 30,
    ping_timeout: 60
  },
  http: {
    port: 8000,
    allow_origin: '*'
  },
  trans: {
    ffmpeg: '/usr/bin/ffmpeg',
    tasks: [
      {
        app: 'live',
        hls: true,
        hlsFlags: '[hls_time=2:hls_list_size=3:hls_flags=delete_segments]',
        dash: false
      }
    ]
  }
};

const nms = new NodeMediaServer(config);
nms.run();

// Now can stream to: rtmp://localhost/live/camera1
// And access HLS at: http://localhost:8000/live/camera1/index.m3u8
```

**3. Frontend Player:**

```tsx
// React Component using video.js with HLS
import videojs from 'video.js';
import 'video.js/dist/video-js.css';

function LiveFeedPlayer({ streamUrl, cameraName }) {
  const videoRef = useRef(null);
  const playerRef = useRef(null);

  useEffect(() => {
    if (!playerRef.current) {
      const videoElement = videoRef.current;
      
      if (!videoElement) return;

      playerRef.current = videojs(videoElement, {
        autoplay: true,
        controls: true,
        responsive: true,
        fluid: true,
        sources: [{
          src: streamUrl,
          type: 'application/x-mpegURL'
        }]
      });
    }

    return () => {
      if (playerRef.current) {
        playerRef.current.dispose();
        playerRef.current = null;
      }
    };
  }, [streamUrl]);

  return (
    <div className="live-feed">
      <div data-vjs-player>
        <video ref={videoRef} className="video-js vjs-big-play-centered" />
      </div>
      <div className="feed-info">
        <h3>{cameraName}</h3>
        <span className="live-badge">🔴 LIVE</span>
      </div>
    </div>
  );
}
```

**4. Multiple Camera Grid:**
```tsx
function LiveFarmFeeds() {
  const cameras = [
    { id: 1, name: 'Main Barn', streamUrl: '/streams/camera1/stream.m3u8' },
    { id: 2, name: 'Milking Area', streamUrl: '/streams/camera2/stream.m3u8' },
    { id: 3, name: 'Calf Area', streamUrl: '/streams/camera3/stream.m3u8' },
    { id: 4, name: 'Pasture', streamUrl: '/streams/camera4/stream.m3u8' }
  ];

  const [selectedCamera, setSelectedCamera] = useState(cameras[0]);

  return (
    <div className="live-feeds-container">
      <div className="main-feed">
        <LiveFeedPlayer
          streamUrl={selectedCamera.streamUrl}
          cameraName={selectedCamera.name}
        />
      </div>
      
      <div className="camera-thumbnails">
        {cameras.map(camera => (
          <button
            key={camera.id}
            className={selectedCamera.id === camera.id ? 'active' : ''}
            onClick={() => setSelectedCamera(camera)}
          >
            <img src={`/thumbs/${camera.id}.jpg`} alt={camera.name} />
            <span>{camera.name}</span>
          </button>
        ))}
      </div>
      
      <div className="feed-controls">
        <button onClick={() => refreshStream()}>🔄 Refresh</button>
        <button onClick={() => toggleFullscreen()}>⛶ Fullscreen</button>
      </div>
    </div>
  );
}
```

**5. Fallback for Offline Streams:**
```javascript
function checkStreamStatus(streamUrl) {
  return fetch(streamUrl, { method: 'HEAD' })
    .then(response => response.ok)
    .catch(() => false);
}

// In component
const [isOnline, setIsOnline] = useState(true);

useEffect(() => {
  const interval = setInterval(async () => {
    const online = await checkStreamStatus(streamUrl);
    setIsOnline(online);
  }, 30000);  // check every 30 seconds

  return () => clearInterval(interval);
}, [streamUrl]);

// Display offline message
{!isOnline && (
  <div className="stream-offline">
    <p>📵 Camera currently offline</p>
    <button onClick={refreshStream}>Try Again</button>
  </div>
)}
```

**Privacy & Security:**
- Don't show streams that could identify individuals
- Use low resolution for public streams
- Implement IP whitelisting for sensitive camera access
- Add watermark to streams
- Log stream access

**Acceptance Criteria:**
- ✓ Live streams load and play
- ✓ Multiple camera selection works
- ✓ Offline message shows when stream unavailable
- ✓ Fullscreen mode works
- ✓ Mobile-friendly player

---

### 5.3 Real-Time Farm Dashboard

**Priority:** Medium | **Phase:** 2 | **Effort:** 4 days

**Mock Data (for development):**
```javascript
// api/farm-data.js
function generateMockFarmData() {
  return {
    timestamp: new Date().toISOString(),
    temperature: 24 + Math.random() * 4,  // 24-28°C
    humidity: 60 + Math.random() * 15,    // 60-75%
    cowComfortIndex: 75 + Math.random() * 15,  // 75-90
    activeCowsMonitored: 150 + Math.floor(Math.random() * 10),
    milkProductionToday: 2500 + Math.floor(Math.random() * 500),  // liters
    averageMilkYieldPerCow: 16 + Math.random() * 2,  // liters/cow
    feedConsumptionRate: 3500 + Math.floor(Math.random() * 200),  // kg/day
    solarEnergyGeneration: Math.max(0, 50 + Math.random() * 100),  // kW
    trend24h: generateTrend24Hours()
  };
}

function generateTrend24Hours() {
  const data = [];
  const now = new Date();
  
  for (let i = 23; i >= 0; i--) {
    const timestamp = new Date(now.getTime() - (i * 60 * 60 * 1000));
    data.push({
      time: timestamp.toISOString(),
      temperature: 24 + Math.random() * 4,
      humidity: 60 + Math.random() * 15,
      milkProduction: 100 + Math.random() * 50
    });
  }
  
  return data;
}

// API endpoint
app.get('/api/farm-data/realtime', (req, res) => {
  res.json(generateMockFarmData());
});
```

**Frontend Dashboard Component:**
```tsx
function FarmDataDashboard() {
  const [farmData, setFarmData] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchFarmData();
    
    // Auto-refresh every 60 seconds
    const interval = setInterval(fetchFarmData, 60000);
    
    return () => clearInterval(interval);
  }, []);

  async function fetchFarmData() {
    try {
      const response = await fetch('/api/farm-data/realtime');
      const data = await response.json();
      setFarmData(data);
      setLoading(false);
    } catch (error) {
      console.error('Failed to fetch farm data:', error);
    }
  }

  if (loading) return <div>Loading farm data...</div>;

  return (
    <div className="farm-dashboard">
      <div className="dashboard-header">
        <h2>Live Farm Metrics</h2>
        <span className="last-updated">
          Last updated: {new Date(farmData.timestamp).toLocaleTimeString()}
        </span>
      </div>

      <div className="metrics-grid">
        <MetricCard
          title="Temperature"
          value={`${farmData.temperature.toFixed(1)}°C`}
          icon="🌡️"
          status="normal"
        />
        <MetricCard
          title="Humidity"
          value={`${farmData.humidity.toFixed(0)}%`}
          icon="💧"
          status="normal"
        />
        <MetricCard
          title="Cow Comfort Index"
          value={farmData.cowComfortIndex.toFixed(0)}
          icon="🐄"
          status={farmData.cowComfortIndex > 80 ? 'good' : 'normal'}
        />
        <MetricCard
          title="Active Cows"
          value={farmData.activeCowsMonitored}
          icon="📊"
        />
        <MetricCard
          title="Milk Production Today"
          value={`${farmData.milkProductionToday.toFixed(0)} L`}
          icon="🥛"
          status="good"
        />
        <MetricCard
          title="Avg Yield per Cow"
          value={`${farmData.averageMilkYieldPerCow.toFixed(1)} L`}
          icon="📈"
        />
        <MetricCard
          title="Feed Consumption"
          value={`${farmData.feedConsumptionRate} kg`}
          icon="🌾"
        />
        <MetricCard
          title="Solar Generation"
          value={`${farmData.solarEnergyGeneration.toFixed(0)} kW`}
          icon="☀️"
          status="good"
        />
      </div>

      <div className="trend-charts">
        <div className="chart-container">
          <h3>Temperature & Humidity (24h)</h3>
          <TemperatureHumidityChart data={farmData.trend24h} />
        </div>
        
        <div className="chart-container">
          <h3>Milk Production Trend (24h)</h3>
          <MilkProductionChart data={farmData.trend24h} />
        </div>
      </div>

      <div className="dashboard-footer">
        <p className="disclaimer">
          * Data shown represents aggregated farm metrics for public display.
          Real-time operational data is monitored separately.
        </p>
      </div>
    </div>
  );
}

function MetricCard({ title, value, icon, status = 'normal' }) {
  const statusColors = {
    good: 'bg-green-100 border-green-300',
    normal: 'bg-blue-100 border-blue-300',
    warning: 'bg-yellow-100 border-yellow-300',
    alert: 'bg-red-100 border-red-300'
  };

  return (
    <div className={`metric-card ${statusColors[status]}`}>
      <div className="metric-icon">{icon}</div>
      <div className="metric-content">
        <h4>{title}</h4>
        <p className="metric-value">{value}</p>
      </div>
    </div>
  );
}
```

**Charts using Recharts:**
```tsx
import { LineChart, Line, XAxis, YAxis, Tooltip, Legend, ResponsiveContainer } from 'recharts';

function TemperatureHumidityChart({ data }) {
  return (
    <ResponsiveContainer width="100%" height={300}>
      <LineChart data={data}>
        <XAxis 
          dataKey="time" 
          tickFormatter={(time) => new Date(time).getHours() + ':00'}
        />
        <YAxis yAxisId="left" domain={[20, 30]} />
        <YAxis yAxisId="right" orientation="right" domain={[50, 80]} />
        <Tooltip 
          labelFormatter={(time) => new Date(time).toLocaleString()}
          formatter={(value) => value.toFixed(1)}
        />
        <Legend />
        <Line 
          yAxisId="left"
          type="monotone" 
          dataKey="temperature" 
          stroke="#f59e0b" 
          name="Temperature (°C)"
        />
        <Line 
          yAxisId="right"
          type="monotone" 
          dataKey="humidity" 
          stroke="#3b82f6" 
          name="Humidity (%)"
        />
      </LineChart>
    </ResponsiveContainer>
  );
}

function MilkProductionChart({ data }) {
  return (
    <ResponsiveContainer width="100%" height={300}>
      <LineChart data={data}>
        <XAxis 
          dataKey="time" 
          tickFormatter={(time) => new Date(time).getHours() + ':00'}
        />
        <YAxis domain={[0, 200]} />
        <Tooltip 
          labelFormatter={(time) => new Date(time).toLocaleString()}
          formatter={(value) => `${value.toFixed(0)} L`}
        />
        <Legend />
        <Line 
          type="monotone" 
          dataKey="milkProduction" 
          stroke="#10b981" 
          name="Production (L/hour)"
          strokeWidth={2}
        />
      </LineChart>
    </ResponsiveContainer>
  );
}
```

**Real Integration (when farm system API available):**
```javascript
// If Smart Dairy has actual IoT system API
async function fetchRealFarmData() {
  // Example: Fetch from Smart Dairy's farm management system
  const response = await fetch('https://farm-system.smartdairy.com/api/metrics', {
    headers: {
      'Authorization': `Bearer ${process.env.FARM_API_KEY}`
    }
  });
  
  const rawData = await response.json();
  
  // Transform to our format
  return {
    timestamp: rawData.timestamp,
    temperature: rawData.barn_sensors.temperature,
    humidity: rawData.barn_sensors.humidity,
    cowComfortIndex: calculateComfortIndex(rawData),
    activeCowsMonitored: rawData.herd_status.active_cows,
    milkProductionToday: rawData.production.total_liters_today,
    // ... map other fields
  };
}
```

**Acceptance Criteria:**
- ✓ Dashboard displays current metrics
- ✓ Data auto-refreshes every 60 seconds
- ✓ Charts show 24-hour trends
- ✓ Responsive design works on mobile
- ✓ Loading state displays properly
- ✓ Error handling for failed requests

---

*Continue in separate document for remaining features...*

**Next Document Will Cover:**
- Content Management System (Blog, Pages)
- Subscription System
- Reviews & Ratings
- Email/SMS Integration
- Analytics Integration
- Security Measures
- Testing Strategy

**Document Status:** Part 2 - Advanced Features  
**Next:** Part 3 - Integrations & Security
