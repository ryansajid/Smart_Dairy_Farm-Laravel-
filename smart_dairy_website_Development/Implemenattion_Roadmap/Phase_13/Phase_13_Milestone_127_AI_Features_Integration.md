# Milestone 127: AI Features Integration

## Smart Dairy Digital Smart Portal + ERP - Phase 13: Performance & AI Optimization

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 127 of 150 (7 of 10 in Phase 13)                                       |
| **Title**        | AI Features Integration                                                |
| **Phase**        | Phase 13 - Optimization: Performance & AI                              |
| **Days**         | Days 631-635 (of 750 total)                                            |
| **Duration**     | 5 working days                                                         |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Integrate AI-powered features into the Smart Dairy platform including product recommendations, anomaly detection for IoT sensors, smart search with NLP, and personalized customer experiences.

### 1.2 Objectives

1. Implement product recommendation engine with collaborative filtering
2. Deploy anomaly detection for IoT sensor data (temperature, milk flow)
3. Create smart search with NLP understanding for Bangla and English
4. Implement personalization engine for customer experiences
5. Build automated insights generation for farm management
6. Create AI-powered chatbot enhancements
7. Achieve >5% recommendation click-through rate
8. Detect 90%+ of IoT anomalies with <5% false positive rate

### 1.3 Key Deliverables

| Deliverable                       | Owner  | Format            | Due Day |
| --------------------------------- | ------ | ----------------- | ------- |
| Recommendation Engine             | Dev 1  | Python module     | 632     |
| Anomaly Detection Service         | Dev 1  | Python module     | 633     |
| Smart Search API                  | Dev 1  | REST/Python       | 633     |
| Personalization Engine            | Dev 2  | Python module     | 634     |
| Insights Generation Service       | Dev 2  | Python module     | 634     |
| Recommendation UI Components      | Dev 3  | React components  | 635     |
| AI Features Documentation         | All    | Markdown          | 635     |

### 1.4 Success Criteria

- [ ] Recommendation CTR >5%
- [ ] Anomaly detection precision >90%
- [ ] Search relevance score >0.8
- [ ] Personalization A/B test positive
- [ ] All AI features with <200ms response time
- [ ] Chatbot resolution rate improved by 20%

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------------- |
| RFP-AI-002   | RFP    | Product recommendations                  | 631-632 | Recommendation engine       |
| RFP-AI-003   | RFP    | Predictive maintenance for IoT           | 633     | Anomaly detection           |
| BRD-AI-002   | BRD    | 15% increase in average order value      | 631-632 | Recommendations             |
| SRS-ML-001   | SRS    | ML model accuracy >85%                   | 631-635 | All AI features             |

---

## 3. Day-by-Day Breakdown

### Day 631-632 - Recommendation Engine

**Objective:** Implement product recommendation system using collaborative and content-based filtering.

#### Dev 1 - Backend Lead (16h over 2 days)

**Task: Recommendation Engine (16h)**

```python
# ml/recommendations/engine.py
import numpy as np
import pandas as pd
from scipy.sparse import csr_matrix
from sklearn.neighbors import NearestNeighbors
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from typing import List, Dict, Optional, Tuple
import redis
import logging

_logger = logging.getLogger(__name__)

class RecommendationEngine:
    """Hybrid recommendation engine for Smart Dairy products"""

    def __init__(self, redis_client: redis.Redis = None):
        self.redis = redis_client
        self.user_item_matrix = None
        self.item_features = None
        self.knn_model = None
        self.tfidf_vectorizer = None
        self.product_vectors = None

    def train(self, orders_df: pd.DataFrame, products_df: pd.DataFrame):
        """Train recommendation models"""
        _logger.info("Training recommendation engine...")

        # Build user-item interaction matrix
        self._build_user_item_matrix(orders_df)

        # Build content-based features
        self._build_content_features(products_df)

        # Train collaborative filtering model
        self._train_collaborative_model()

        _logger.info("Recommendation engine training complete")

    def _build_user_item_matrix(self, orders_df: pd.DataFrame):
        """Build user-item interaction matrix"""
        # Aggregate purchases
        interactions = orders_df.groupby(['customer_id', 'product_id']).agg({
            'quantity': 'sum',
            'order_id': 'count'
        }).reset_index()

        interactions['score'] = (
            interactions['quantity'] * 0.5 +
            interactions['order_id'] * 0.5
        )

        # Create sparse matrix
        self.user_ids = interactions['customer_id'].unique()
        self.product_ids = interactions['product_id'].unique()

        user_idx = {uid: i for i, uid in enumerate(self.user_ids)}
        product_idx = {pid: i for i, pid in enumerate(self.product_ids)}

        rows = interactions['customer_id'].map(user_idx)
        cols = interactions['product_id'].map(product_idx)
        data = interactions['score']

        self.user_item_matrix = csr_matrix(
            (data, (rows, cols)),
            shape=(len(self.user_ids), len(self.product_ids))
        )

        self.user_idx_map = user_idx
        self.product_idx_map = product_idx
        self.idx_product_map = {v: k for k, v in product_idx.items()}

    def _build_content_features(self, products_df: pd.DataFrame):
        """Build content-based product features"""
        # Combine text features
        products_df['text_features'] = (
            products_df['name'].fillna('') + ' ' +
            products_df['description'].fillna('') + ' ' +
            products_df['category_name'].fillna('')
        )

        self.tfidf_vectorizer = TfidfVectorizer(
            max_features=500,
            stop_words='english',
            ngram_range=(1, 2)
        )

        self.product_vectors = self.tfidf_vectorizer.fit_transform(
            products_df['text_features']
        )

        self.products_df = products_df

    def _train_collaborative_model(self):
        """Train KNN model for collaborative filtering"""
        self.knn_model = NearestNeighbors(
            metric='cosine',
            algorithm='brute',
            n_neighbors=20
        )
        self.knn_model.fit(self.user_item_matrix.T)

    def get_recommendations(self, customer_id: int, n: int = 10,
                           exclude_purchased: bool = True) -> List[Dict]:
        """Get personalized recommendations for a customer"""
        # Check cache
        cache_key = f"recommendations:{customer_id}"
        if self.redis:
            cached = self.redis.get(cache_key)
            if cached:
                return json.loads(cached)

        # Collaborative filtering recommendations
        cf_recs = self._get_cf_recommendations(customer_id, n * 2)

        # Content-based recommendations based on purchase history
        cb_recs = self._get_content_recommendations(customer_id, n * 2)

        # Combine and rank
        combined = self._combine_recommendations(cf_recs, cb_recs, n)

        # Exclude already purchased if requested
        if exclude_purchased and customer_id in self.user_idx_map:
            user_idx = self.user_idx_map[customer_id]
            purchased = set(self.user_item_matrix[user_idx].nonzero()[1])
            combined = [r for r in combined if r['product_id'] not in purchased]

        recommendations = combined[:n]

        # Cache results
        if self.redis:
            self.redis.setex(cache_key, 300, json.dumps(recommendations))

        return recommendations

    def _get_cf_recommendations(self, customer_id: int, n: int) -> List[Tuple[int, float]]:
        """Get collaborative filtering recommendations"""
        if customer_id not in self.user_idx_map:
            return []

        user_idx = self.user_idx_map[customer_id]
        user_vector = self.user_item_matrix[user_idx]

        # Find similar users
        distances, indices = self.knn_model.kneighbors(
            user_vector.reshape(1, -1).toarray(),
            n_neighbors=20
        )

        # Aggregate recommendations from similar users
        recommendations = {}
        for dist, idx in zip(distances[0], indices[0]):
            similarity = 1 - dist
            item_scores = self.user_item_matrix[:, idx].toarray().flatten()

            for item_idx, score in enumerate(item_scores):
                if score > 0:
                    if item_idx not in recommendations:
                        recommendations[item_idx] = 0
                    recommendations[item_idx] += score * similarity

        return sorted(recommendations.items(), key=lambda x: x[1], reverse=True)[:n]

    def _get_content_recommendations(self, customer_id: int, n: int) -> List[Tuple[int, float]]:
        """Get content-based recommendations"""
        if customer_id not in self.user_idx_map:
            return self._get_popular_products(n)

        user_idx = self.user_idx_map[customer_id]
        purchased_items = self.user_item_matrix[user_idx].nonzero()[1]

        if len(purchased_items) == 0:
            return self._get_popular_products(n)

        # Get average vector of purchased items
        user_profile = self.product_vectors[purchased_items].mean(axis=0)
        user_profile = np.asarray(user_profile).reshape(1, -1)

        # Find similar products
        similarities = cosine_similarity(user_profile, self.product_vectors)[0]

        # Return top similar products
        similar_indices = similarities.argsort()[::-1]
        return [(idx, similarities[idx]) for idx in similar_indices[:n]]

    def _get_popular_products(self, n: int) -> List[Tuple[int, float]]:
        """Get popular products as fallback"""
        popularity = self.user_item_matrix.sum(axis=0).A1
        popular_indices = popularity.argsort()[::-1][:n]
        return [(idx, popularity[idx]) for idx in popular_indices]

    def _combine_recommendations(self, cf_recs: List, cb_recs: List, n: int) -> List[Dict]:
        """Combine and rank recommendations from different sources"""
        combined_scores = {}

        # CF recommendations with weight 0.6
        for item_idx, score in cf_recs:
            product_id = self.idx_product_map.get(item_idx)
            if product_id:
                combined_scores[product_id] = combined_scores.get(product_id, 0) + score * 0.6

        # CB recommendations with weight 0.4
        for item_idx, score in cb_recs:
            product_id = self.idx_product_map.get(item_idx)
            if product_id:
                combined_scores[product_id] = combined_scores.get(product_id, 0) + score * 0.4

        # Sort by combined score
        sorted_recs = sorted(combined_scores.items(), key=lambda x: x[1], reverse=True)

        # Build response
        recommendations = []
        for product_id, score in sorted_recs[:n]:
            product = self.products_df[self.products_df['id'] == product_id].iloc[0]
            recommendations.append({
                'product_id': int(product_id),
                'name': product['name'],
                'price': float(product['list_price']),
                'score': float(score),
                'category': product.get('category_name', '')
            })

        return recommendations
```

---

### Day 633 - Anomaly Detection & Smart Search

**Objective:** Implement IoT anomaly detection and NLP-powered search.

#### Dev 1 - Backend Lead (8h)

**Task 1: Anomaly Detection Service (4h)**

```python
# ml/anomaly/detector.py
import numpy as np
import pandas as pd
from sklearn.ensemble import IsolationForest
from sklearn.preprocessing import StandardScaler
from typing import Dict, List, Optional, Tuple
from dataclasses import dataclass
import logging

_logger = logging.getLogger(__name__)

@dataclass
class AnomalyResult:
    sensor_id: str
    timestamp: str
    value: float
    is_anomaly: bool
    anomaly_score: float
    anomaly_type: str
    severity: str  # 'low', 'medium', 'high', 'critical'
    recommendation: str

class IoTAnomalyDetector:
    """Detect anomalies in IoT sensor data"""

    SENSOR_CONFIGS = {
        'temperature': {
            'min_threshold': 2.0,
            'max_threshold': 8.0,
            'unit': '°C',
            'critical_min': 0.0,
            'critical_max': 15.0
        },
        'milk_flow': {
            'min_threshold': 0.5,
            'max_threshold': 20.0,
            'unit': 'L/min',
            'critical_min': 0.0,
            'critical_max': 30.0
        },
        'humidity': {
            'min_threshold': 40.0,
            'max_threshold': 70.0,
            'unit': '%',
            'critical_min': 20.0,
            'critical_max': 90.0
        }
    }

    def __init__(self):
        self.models: Dict[str, IsolationForest] = {}
        self.scalers: Dict[str, StandardScaler] = {}

    def train(self, sensor_data: pd.DataFrame, sensor_type: str):
        """Train anomaly detection model for a sensor type"""
        _logger.info(f"Training anomaly detector for {sensor_type}")

        # Prepare features
        features = self._extract_features(sensor_data)

        # Scale features
        scaler = StandardScaler()
        scaled_features = scaler.fit_transform(features)
        self.scalers[sensor_type] = scaler

        # Train Isolation Forest
        model = IsolationForest(
            contamination=0.05,
            random_state=42,
            n_estimators=100
        )
        model.fit(scaled_features)
        self.models[sensor_type] = model

    def _extract_features(self, data: pd.DataFrame) -> np.ndarray:
        """Extract features for anomaly detection"""
        features = []

        for _, row in data.iterrows():
            feature_vector = [
                row['value'],
                row.get('rate_of_change', 0),
                row.get('hour', 0),
                row.get('day_of_week', 0)
            ]
            features.append(feature_vector)

        return np.array(features)

    def detect(self, sensor_id: str, sensor_type: str,
               value: float, timestamp: str,
               context: Dict = None) -> AnomalyResult:
        """Detect if a sensor reading is anomalous"""
        config = self.SENSOR_CONFIGS.get(sensor_type, {})

        # Rule-based detection first
        is_anomaly, anomaly_type, severity = self._rule_based_check(
            value, sensor_type, config
        )

        # ML-based detection for subtle anomalies
        if not is_anomaly and sensor_type in self.models:
            ml_anomaly, ml_score = self._ml_based_check(
                value, sensor_type, context
            )
            if ml_anomaly:
                is_anomaly = True
                anomaly_type = 'pattern_deviation'
                severity = 'medium'

        # Generate recommendation
        recommendation = self._generate_recommendation(
            sensor_type, anomaly_type, severity, value, config
        )

        return AnomalyResult(
            sensor_id=sensor_id,
            timestamp=timestamp,
            value=value,
            is_anomaly=is_anomaly,
            anomaly_score=abs(value - (config.get('max_threshold', 0) + config.get('min_threshold', 0)) / 2),
            anomaly_type=anomaly_type,
            severity=severity,
            recommendation=recommendation
        )

    def _rule_based_check(self, value: float, sensor_type: str,
                         config: Dict) -> Tuple[bool, str, str]:
        """Rule-based anomaly detection"""
        if not config:
            return False, '', ''

        # Critical thresholds
        if value < config.get('critical_min', float('-inf')):
            return True, 'critically_low', 'critical'
        if value > config.get('critical_max', float('inf')):
            return True, 'critically_high', 'critical'

        # Warning thresholds
        if value < config.get('min_threshold', float('-inf')):
            return True, 'below_threshold', 'high'
        if value > config.get('max_threshold', float('inf')):
            return True, 'above_threshold', 'high'

        return False, '', ''

    def _ml_based_check(self, value: float, sensor_type: str,
                        context: Dict) -> Tuple[bool, float]:
        """ML-based anomaly detection"""
        model = self.models.get(sensor_type)
        scaler = self.scalers.get(sensor_type)

        if not model or not scaler:
            return False, 0.0

        features = np.array([[
            value,
            context.get('rate_of_change', 0) if context else 0,
            context.get('hour', 12) if context else 12,
            context.get('day_of_week', 0) if context else 0
        ]])

        scaled_features = scaler.transform(features)
        prediction = model.predict(scaled_features)[0]
        score = model.score_samples(scaled_features)[0]

        return prediction == -1, abs(score)

    def _generate_recommendation(self, sensor_type: str, anomaly_type: str,
                                 severity: str, value: float, config: Dict) -> str:
        """Generate actionable recommendation"""
        recommendations = {
            'temperature': {
                'above_threshold': f"Milk storage temperature ({value}{config.get('unit', '')}) is too high. Check refrigeration system immediately.",
                'below_threshold': f"Temperature ({value}{config.get('unit', '')}) is below optimal. Verify cooler settings.",
                'critically_high': "CRITICAL: Temperature exceeds safe limit. Move product to cold storage immediately!",
                'critically_low': "CRITICAL: Temperature too low. Check for freezing conditions.",
                'pattern_deviation': "Unusual temperature pattern detected. Schedule maintenance check."
            },
            'milk_flow': {
                'above_threshold': f"Milk flow rate ({value}{config.get('unit', '')}) unusually high. Check for sensor calibration.",
                'below_threshold': f"Low milk flow ({value}{config.get('unit', '')}). Check for blockages or equipment issues.",
                'pattern_deviation': "Unusual flow pattern. Inspect milking equipment."
            }
        }

        return recommendations.get(sensor_type, {}).get(
            anomaly_type,
            f"Anomaly detected: {anomaly_type}. Please investigate."
        )
```

**Task 2: Smart Search with NLP (4h)**

```python
# ml/search/smart_search.py
from typing import List, Dict, Optional
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import numpy as np
import re
import logging

_logger = logging.getLogger(__name__)

class SmartSearchEngine:
    """NLP-powered search for products and content"""

    # Bangla to English mappings for common dairy terms
    BANGLA_MAPPINGS = {
        'দুধ': 'milk',
        'দই': 'yogurt',
        'ঘি': 'ghee',
        'মাখন': 'butter',
        'পনীর': 'cheese',
        'ছানা': 'chhana',
        'মিষ্টি': 'sweet',
        'লাচ্ছি': 'lassi',
        'বোরহানি': 'borhani'
    }

    def __init__(self):
        self.vectorizer = None
        self.product_vectors = None
        self.products_df = None

    def index_products(self, products_df: pd.DataFrame):
        """Index products for search"""
        self.products_df = products_df.copy()

        # Create searchable text
        self.products_df['search_text'] = (
            self.products_df['name'].fillna('') + ' ' +
            self.products_df['description'].fillna('') + ' ' +
            self.products_df['category_name'].fillna('') + ' ' +
            self.products_df.get('tags', '').fillna('')
        )

        # Add Bangla equivalents
        for bangla, english in self.BANGLA_MAPPINGS.items():
            self.products_df['search_text'] = self.products_df['search_text'].str.replace(
                english, f"{english} {bangla}", case=False
            )

        self.vectorizer = TfidfVectorizer(
            max_features=1000,
            ngram_range=(1, 3),
            stop_words='english'
        )

        self.product_vectors = self.vectorizer.fit_transform(
            self.products_df['search_text']
        )

        _logger.info(f"Indexed {len(self.products_df)} products for search")

    def search(self, query: str, n: int = 20,
               filters: Dict = None) -> List[Dict]:
        """Search products with NLP understanding"""
        # Preprocess query
        processed_query = self._preprocess_query(query)

        # Expand query with synonyms
        expanded_query = self._expand_query(processed_query)

        # Vectorize query
        query_vector = self.vectorizer.transform([expanded_query])

        # Calculate similarities
        similarities = cosine_similarity(query_vector, self.product_vectors)[0]

        # Get top results
        top_indices = similarities.argsort()[::-1]

        results = []
        for idx in top_indices:
            if similarities[idx] < 0.01:  # Minimum threshold
                break

            product = self.products_df.iloc[idx]

            # Apply filters
            if filters and not self._matches_filters(product, filters):
                continue

            results.append({
                'product_id': int(product['id']),
                'name': product['name'],
                'description': product.get('description', '')[:200],
                'price': float(product['list_price']),
                'category': product.get('category_name', ''),
                'score': float(similarities[idx])
            })

            if len(results) >= n:
                break

        return results

    def _preprocess_query(self, query: str) -> str:
        """Preprocess search query"""
        # Convert Bangla terms to English
        for bangla, english in self.BANGLA_MAPPINGS.items():
            query = query.replace(bangla, english)

        # Lowercase and clean
        query = query.lower().strip()
        query = re.sub(r'[^\w\s]', ' ', query)

        return query

    def _expand_query(self, query: str) -> str:
        """Expand query with synonyms and related terms"""
        synonyms = {
            'milk': 'milk dairy fresh pure organic',
            'yogurt': 'yogurt dahi curd probiotic',
            'ghee': 'ghee clarified butter desi',
            'cheese': 'cheese paneer cottage',
            'butter': 'butter makhan cream',
            'organic': 'organic natural pure chemical-free'
        }

        expanded = query
        for term, expansion in synonyms.items():
            if term in query:
                expanded = expanded.replace(term, expansion)

        return expanded

    def _matches_filters(self, product: pd.Series, filters: Dict) -> bool:
        """Check if product matches filters"""
        if 'category' in filters and filters['category']:
            if product.get('category_name', '') != filters['category']:
                return False

        if 'min_price' in filters:
            if product['list_price'] < filters['min_price']:
                return False

        if 'max_price' in filters:
            if product['list_price'] > filters['max_price']:
                return False

        if 'in_stock' in filters and filters['in_stock']:
            if product.get('qty_available', 0) <= 0:
                return False

        return True

    def get_suggestions(self, partial_query: str, n: int = 5) -> List[str]:
        """Get search suggestions as user types"""
        if len(partial_query) < 2:
            return []

        # Search for matching product names
        matches = self.products_df[
            self.products_df['name'].str.lower().str.contains(
                partial_query.lower(), na=False
            )
        ]['name'].unique()[:n]

        return list(matches)
```

---

### Day 634-635 - Personalization & UI Integration

#### Dev 2 - Full-Stack Developer (16h)

**Task: Personalization Engine (8h)**

```python
# ml/personalization/engine.py
from typing import Dict, List, Optional
import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)

class PersonalizationEngine:
    """Personalize customer experience based on behavior"""

    def __init__(self, cache_service=None):
        self.cache = cache_service

    def get_personalized_content(self, customer_id: int,
                                  customer_data: Dict = None) -> Dict:
        """Get personalized content for a customer"""
        # Check cache
        cache_key = f"personalization:{customer_id}"
        if self.cache:
            cached = self.cache.get(cache_key)
            if cached:
                return cached

        personalization = {
            'greeting': self._get_personalized_greeting(customer_data),
            'featured_products': self._get_featured_for_customer(customer_id),
            'promotions': self._get_relevant_promotions(customer_data),
            'content_order': self._get_content_priority(customer_data),
            'ui_preferences': self._get_ui_preferences(customer_data)
        }

        if self.cache:
            self.cache.set(cache_key, personalization, ttl=300)

        return personalization

    def _get_personalized_greeting(self, customer_data: Dict) -> str:
        """Generate personalized greeting"""
        if not customer_data:
            return "Welcome to Smart Dairy!"

        name = customer_data.get('name', 'Valued Customer')
        first_name = name.split()[0] if name else 'there'

        hour = datetime.now().hour
        if hour < 12:
            time_greeting = "Good morning"
        elif hour < 17:
            time_greeting = "Good afternoon"
        else:
            time_greeting = "Good evening"

        # Check if loyal customer
        if customer_data.get('order_count', 0) > 10:
            return f"{time_greeting}, {first_name}! Welcome back, valued member!"

        return f"{time_greeting}, {first_name}!"

    def _get_featured_for_customer(self, customer_id: int) -> List[int]:
        """Get featured product IDs for customer"""
        # Would integrate with recommendation engine
        return []

    def _get_relevant_promotions(self, customer_data: Dict) -> List[Dict]:
        """Get promotions relevant to customer"""
        promotions = []

        if not customer_data:
            return [{'type': 'first_order', 'discount': 10, 'message': 'Welcome! Get 10% off your first order'}]

        # Subscription promotion for non-subscribers
        if not customer_data.get('has_subscription'):
            promotions.append({
                'type': 'subscription',
                'message': 'Subscribe and save 15% on daily milk delivery!'
            })

        # Win-back for inactive customers
        last_order_date = customer_data.get('last_order_date')
        if last_order_date:
            days_since = (datetime.now() - pd.to_datetime(last_order_date)).days
            if days_since > 30:
                promotions.append({
                    'type': 'win_back',
                    'discount': 20,
                    'message': 'We miss you! Here\'s 20% off your next order'
                })

        return promotions

    def _get_content_priority(self, customer_data: Dict) -> List[str]:
        """Determine content display priority"""
        default_order = ['recommendations', 'categories', 'promotions', 'new_arrivals']

        if not customer_data:
            return default_order

        # Adjust based on customer segment
        segment = customer_data.get('segment', 'general')

        if segment == 'subscription':
            return ['subscription_status', 'recommendations', 'new_arrivals', 'promotions']
        elif segment == 'b2b':
            return ['bulk_orders', 'price_lists', 'order_history', 'promotions']

        return default_order

    def _get_ui_preferences(self, customer_data: Dict) -> Dict:
        """Get UI customization preferences"""
        return {
            'language': customer_data.get('preferred_language', 'en') if customer_data else 'en',
            'currency': 'BDT',
            'theme': customer_data.get('preferred_theme', 'light') if customer_data else 'light',
            'show_prices_with_tax': True
        }
```

#### Dev 3 - Frontend Lead (16h)

**Task: AI Feature UI Components (16h)**

```tsx
// frontend/src/components/ai/RecommendationCarousel.tsx
import React from 'react';
import { Card, Carousel, Button, Tag, Skeleton } from 'antd';
import { useQuery } from '@tanstack/react-query';
import { ShoppingCartOutlined } from '@ant-design/icons';

interface Recommendation {
  product_id: number;
  name: string;
  price: number;
  score: number;
  category: string;
  image_url?: string;
}

interface RecommendationCarouselProps {
  customerId: number;
  title?: string;
  onAddToCart?: (productId: number) => void;
}

const RecommendationCarousel: React.FC<RecommendationCarouselProps> = ({
  customerId,
  title = "Recommended for You",
  onAddToCart
}) => {
  const { data: recommendations, isLoading } = useQuery<Recommendation[]>({
    queryKey: ['recommendations', customerId],
    queryFn: () => fetch(`/api/v1/recommendations/${customerId}`).then(r => r.json()),
    staleTime: 5 * 60 * 1000
  });

  if (isLoading) {
    return (
      <div className="recommendation-carousel">
        <h3>{title}</h3>
        <Skeleton active paragraph={{ rows: 3 }} />
      </div>
    );
  }

  if (!recommendations?.length) {
    return null;
  }

  return (
    <div className="recommendation-carousel">
      <h3>{title}</h3>
      <Carousel
        autoplay
        dots={false}
        slidesToShow={4}
        responsive={[
          { breakpoint: 1024, settings: { slidesToShow: 3 } },
          { breakpoint: 768, settings: { slidesToShow: 2 } },
          { breakpoint: 480, settings: { slidesToShow: 1 } }
        ]}
      >
        {recommendations.map(rec => (
          <div key={rec.product_id} className="recommendation-item">
            <Card
              hoverable
              cover={
                <img
                  alt={rec.name}
                  src={rec.image_url || '/placeholder-product.png'}
                  style={{ height: 150, objectFit: 'cover' }}
                />
              }
              actions={[
                <Button
                  type="primary"
                  icon={<ShoppingCartOutlined />}
                  onClick={() => onAddToCart?.(rec.product_id)}
                >
                  Add
                </Button>
              ]}
            >
              <Card.Meta
                title={rec.name}
                description={
                  <>
                    <div className="price">৳{rec.price.toFixed(2)}</div>
                    <Tag color="blue">{rec.category}</Tag>
                  </>
                }
              />
            </Card>
          </div>
        ))}
      </Carousel>
    </div>
  );
};

export default RecommendationCarousel;
```

---

## 4. Technical Specifications

### 4.1 AI Features Configuration

| Feature | Algorithm | Response Time | Accuracy Target |
|---------|-----------|---------------|-----------------|
| Recommendations | Hybrid CF+CB | <100ms | CTR >5% |
| Anomaly Detection | Isolation Forest | <50ms | Precision >90% |
| Smart Search | TF-IDF + NLP | <100ms | Relevance >0.8 |
| Personalization | Rule-based + ML | <50ms | Engagement +15% |

---

## 5. Success Criteria Validation

- [ ] Recommendation CTR >5%
- [ ] Anomaly detection precision >90%
- [ ] Search relevance score >0.8
- [ ] AI features response time <200ms
- [ ] All features integrated and tested

---

**End of Milestone 127 Document**
