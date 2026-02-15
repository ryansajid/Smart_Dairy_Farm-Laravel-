# Milestone 128: Computer Vision & Image AI

## Smart Dairy Digital Smart Portal + ERP - Phase 13: Performance & AI Optimization

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 128 of 150 (8 of 10 in Phase 13)                                       |
| **Title**        | Computer Vision & Image AI                                             |
| **Phase**        | Phase 13 - Optimization: Performance & AI                              |
| **Days**         | Days 636-640 (of 750 total)                                            |
| **Duration**     | 5 working days                                                         |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Develop and deploy computer vision models for automated quality assessment, animal identification, and body condition scoring to enhance farm management efficiency and product quality control.

### 1.2 Objectives

1. Develop milk quality assessment model (color, consistency detection)
2. Create animal identification system using ear tag/RFID image recognition
3. Implement body condition scoring (BCS) model for cattle health assessment
4. Build feed quality assessment capability
5. Create image preprocessing and edge deployment pipeline
6. Achieve 85%+ accuracy on all CV models
7. Optimize for mobile deployment (Flutter app integration)

### 1.3 Key Deliverables

| Deliverable                       | Owner  | Format            | Due Day |
| --------------------------------- | ------ | ----------------- | ------- |
| Milk Quality Assessment Model     | Dev 1  | TensorFlow/ONNX   | 637     |
| Animal ID Recognition Model       | Dev 1  | TensorFlow/ONNX   | 638     |
| Body Condition Scoring Model      | Dev 1  | TensorFlow/ONNX   | 639     |
| Image Preprocessing Pipeline      | Dev 2  | Python module     | 637     |
| Edge Deployment Configuration     | Dev 2  | TF Lite/Config    | 639     |
| CV Results UI Components          | Dev 3  | React/Flutter     | 640     |
| CV Model Documentation            | All    | Markdown          | 640     |

### 1.4 Success Criteria

- [ ] Milk quality assessment accuracy >85%
- [ ] Animal ID recognition accuracy >90%
- [ ] BCS scoring accuracy >85% (within ±0.25 score)
- [ ] Model inference time <500ms on mobile devices
- [ ] Models exported for TensorFlow Lite deployment
- [ ] Integration with farm mobile app complete

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------------- |
| RFP-AI-004   | RFP    | Quality assessment automation            | 636-637 | Milk quality model          |
| BRD-FARM-003 | BRD    | Automated animal identification          | 638     | Animal ID model             |
| SRS-ML-001   | SRS    | ML model accuracy >85%                   | 636-640 | All CV models               |

---

## 3. Day-by-Day Breakdown

### Day 636-637 - Milk Quality Assessment

**Objective:** Develop CV model for milk quality assessment.

#### Dev 1 - Backend Lead (16h)

**Task: Milk Quality Assessment Model (16h)**

```python
# ml/vision/milk_quality.py
import tensorflow as tf
from tensorflow.keras import layers, models
from tensorflow.keras.applications import MobileNetV2
from tensorflow.keras.preprocessing.image import ImageDataGenerator
import numpy as np
from typing import Dict, Tuple, List
import cv2
import logging

_logger = logging.getLogger(__name__)

class MilkQualityAssessor:
    """Assess milk quality from images"""

    QUALITY_CLASSES = ['excellent', 'good', 'acceptable', 'poor', 'reject']

    # Quality indicators based on visual characteristics
    QUALITY_INDICATORS = {
        'color': {
            'normal': (240, 250),  # RGB white range
            'yellowish': (230, 240),
            'bluish': (220, 235)
        },
        'consistency': ['uniform', 'clumpy', 'watery', 'thick']
    }

    def __init__(self, model_path: str = None):
        self.model = None
        self.input_shape = (224, 224, 3)
        if model_path:
            self.load_model(model_path)

    def build_model(self):
        """Build milk quality assessment model"""
        # Use MobileNetV2 as base for efficiency
        base_model = MobileNetV2(
            input_shape=self.input_shape,
            include_top=False,
            weights='imagenet'
        )

        # Freeze base model layers
        base_model.trainable = False

        # Build classification head
        model = models.Sequential([
            base_model,
            layers.GlobalAveragePooling2D(),
            layers.Dense(256, activation='relu'),
            layers.Dropout(0.3),
            layers.Dense(128, activation='relu'),
            layers.Dropout(0.2),
            layers.Dense(len(self.QUALITY_CLASSES), activation='softmax')
        ])

        model.compile(
            optimizer=tf.keras.optimizers.Adam(learning_rate=0.001),
            loss='categorical_crossentropy',
            metrics=['accuracy']
        )

        self.model = model
        return model

    def train(self, train_dir: str, val_dir: str, epochs: int = 20):
        """Train the milk quality model"""
        _logger.info("Training milk quality assessment model...")

        # Data augmentation
        train_datagen = ImageDataGenerator(
            rescale=1./255,
            rotation_range=20,
            width_shift_range=0.2,
            height_shift_range=0.2,
            shear_range=0.2,
            zoom_range=0.2,
            horizontal_flip=True,
            brightness_range=[0.8, 1.2]
        )

        val_datagen = ImageDataGenerator(rescale=1./255)

        train_generator = train_datagen.flow_from_directory(
            train_dir,
            target_size=self.input_shape[:2],
            batch_size=32,
            class_mode='categorical',
            classes=self.QUALITY_CLASSES
        )

        val_generator = val_datagen.flow_from_directory(
            val_dir,
            target_size=self.input_shape[:2],
            batch_size=32,
            class_mode='categorical',
            classes=self.QUALITY_CLASSES
        )

        # Callbacks
        callbacks = [
            tf.keras.callbacks.EarlyStopping(patience=5, restore_best_weights=True),
            tf.keras.callbacks.ReduceLROnPlateau(factor=0.2, patience=3)
        ]

        history = self.model.fit(
            train_generator,
            validation_data=val_generator,
            epochs=epochs,
            callbacks=callbacks
        )

        _logger.info("Training complete")
        return history

    def assess_quality(self, image: np.ndarray) -> Dict:
        """Assess milk quality from image"""
        # Preprocess image
        processed = self._preprocess_image(image)

        # Get model prediction
        prediction = self.model.predict(np.expand_dims(processed, 0))[0]
        quality_class = self.QUALITY_CLASSES[np.argmax(prediction)]
        confidence = float(np.max(prediction))

        # Additional visual analysis
        color_analysis = self._analyze_color(image)
        consistency_analysis = self._analyze_consistency(image)

        return {
            'quality_class': quality_class,
            'confidence': confidence,
            'class_probabilities': dict(zip(self.QUALITY_CLASSES, prediction.tolist())),
            'color_analysis': color_analysis,
            'consistency_analysis': consistency_analysis,
            'recommendation': self._get_recommendation(quality_class, color_analysis)
        }

    def _preprocess_image(self, image: np.ndarray) -> np.ndarray:
        """Preprocess image for model input"""
        # Resize
        resized = cv2.resize(image, self.input_shape[:2])
        # Normalize
        normalized = resized.astype('float32') / 255.0
        return normalized

    def _analyze_color(self, image: np.ndarray) -> Dict:
        """Analyze milk color"""
        # Convert to HSV for better color analysis
        hsv = cv2.cvtColor(image, cv2.COLOR_BGR2HSV)

        # Calculate average color in center region
        h, w = image.shape[:2]
        center = image[h//4:3*h//4, w//4:3*w//4]

        avg_color = center.mean(axis=(0, 1))

        # Determine color category
        brightness = avg_color[2] if len(avg_color) > 2 else avg_color.mean()

        if brightness > 240:
            color_category = 'normal_white'
        elif brightness > 220:
            color_category = 'slightly_yellowish'
        else:
            color_category = 'discolored'

        return {
            'average_rgb': avg_color.tolist(),
            'category': color_category,
            'brightness': float(brightness)
        }

    def _analyze_consistency(self, image: np.ndarray) -> Dict:
        """Analyze milk consistency through texture analysis"""
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

        # Calculate texture metrics
        laplacian_var = cv2.Laplacian(gray, cv2.CV_64F).var()

        if laplacian_var < 100:
            consistency = 'uniform'
        elif laplacian_var < 500:
            consistency = 'slightly_irregular'
        else:
            consistency = 'irregular'

        return {
            'texture_variance': float(laplacian_var),
            'category': consistency
        }

    def _get_recommendation(self, quality_class: str, color_analysis: Dict) -> str:
        """Generate quality recommendation"""
        recommendations = {
            'excellent': "Milk quality is excellent. Proceed with processing.",
            'good': "Milk quality is good. Suitable for all products.",
            'acceptable': "Milk quality is acceptable. Consider for secondary products.",
            'poor': "Milk quality is poor. Investigate source and handling.",
            'reject': "Milk does not meet quality standards. Do not process."
        }
        return recommendations.get(quality_class, "Quality assessment inconclusive.")

    def export_to_tflite(self, output_path: str):
        """Export model to TensorFlow Lite for mobile deployment"""
        converter = tf.lite.TFLiteConverter.from_keras_model(self.model)
        converter.optimizations = [tf.lite.Optimize.DEFAULT]
        tflite_model = converter.convert()

        with open(output_path, 'wb') as f:
            f.write(tflite_model)

        _logger.info(f"Model exported to {output_path}")

    def save_model(self, path: str):
        """Save model"""
        self.model.save(path)

    def load_model(self, path: str):
        """Load model"""
        self.model = tf.keras.models.load_model(path)
```

---

### Day 638 - Animal Identification Model

**Objective:** Develop animal ID recognition from ear tag images.

#### Dev 1 - Backend Lead (8h)

**Task: Animal ID Recognition Model (8h)**

```python
# ml/vision/animal_id.py
import tensorflow as tf
from tensorflow.keras import layers, models
import numpy as np
import cv2
from typing import Dict, Optional, Tuple
import logging

_logger = logging.getLogger(__name__)

class AnimalIDRecognizer:
    """Recognize animal IDs from ear tag images"""

    def __init__(self, model_path: str = None):
        self.detection_model = None
        self.ocr_model = None
        self.input_shape = (224, 224, 3)
        if model_path:
            self.load_model(model_path)

    def build_models(self):
        """Build detection and OCR models"""
        # Ear tag detection model
        self.detection_model = self._build_detection_model()

        # OCR model for reading tag numbers
        self.ocr_model = self._build_ocr_model()

    def _build_detection_model(self):
        """Build ear tag detection model"""
        base_model = tf.keras.applications.MobileNetV2(
            input_shape=self.input_shape,
            include_top=False,
            weights='imagenet'
        )
        base_model.trainable = False

        model = models.Sequential([
            base_model,
            layers.GlobalAveragePooling2D(),
            layers.Dense(256, activation='relu'),
            layers.Dense(4, activation='sigmoid')  # Bounding box coordinates
        ])

        model.compile(
            optimizer='adam',
            loss='mse',
            metrics=['mae']
        )

        return model

    def _build_ocr_model(self):
        """Build OCR model for tag number recognition"""
        # Character set: 0-9, A-Z, -
        num_classes = 37

        model = models.Sequential([
            layers.Input(shape=(64, 200, 1)),
            layers.Conv2D(32, (3, 3), activation='relu', padding='same'),
            layers.MaxPooling2D((2, 2)),
            layers.Conv2D(64, (3, 3), activation='relu', padding='same'),
            layers.MaxPooling2D((2, 2)),
            layers.Conv2D(128, (3, 3), activation='relu', padding='same'),
            layers.Reshape((-1, 128 * 16)),
            layers.Bidirectional(layers.LSTM(128, return_sequences=True)),
            layers.Dense(num_classes, activation='softmax')
        ])

        return model

    def identify_animal(self, image: np.ndarray) -> Dict:
        """Identify animal from ear tag image"""
        # Detect ear tag region
        tag_region, detection_confidence = self._detect_ear_tag(image)

        if tag_region is None:
            return {
                'success': False,
                'error': 'No ear tag detected in image',
                'confidence': 0.0
            }

        # Read tag number
        tag_number, ocr_confidence = self._read_tag_number(tag_region)

        # Parse Bangladesh ear tag format (BD-XXXX-XXXXXX)
        parsed = self._parse_tag_format(tag_number)

        return {
            'success': True,
            'tag_number': tag_number,
            'parsed': parsed,
            'detection_confidence': float(detection_confidence),
            'ocr_confidence': float(ocr_confidence),
            'overall_confidence': float(detection_confidence * ocr_confidence)
        }

    def _detect_ear_tag(self, image: np.ndarray) -> Tuple[Optional[np.ndarray], float]:
        """Detect ear tag in image"""
        # Preprocess
        resized = cv2.resize(image, self.input_shape[:2])
        normalized = resized.astype('float32') / 255.0

        # Predict bounding box
        bbox = self.detection_model.predict(np.expand_dims(normalized, 0))[0]

        # Extract region
        h, w = image.shape[:2]
        x1, y1, x2, y2 = bbox
        x1, x2 = int(x1 * w), int(x2 * w)
        y1, y2 = int(y1 * h), int(y2 * h)

        # Validate bounding box
        if x2 <= x1 or y2 <= y1:
            return None, 0.0

        tag_region = image[y1:y2, x1:x2]
        confidence = 0.9  # Placeholder

        return tag_region, confidence

    def _read_tag_number(self, tag_region: np.ndarray) -> Tuple[str, float]:
        """Read tag number using OCR"""
        # Preprocess for OCR
        gray = cv2.cvtColor(tag_region, cv2.COLOR_BGR2GRAY)
        resized = cv2.resize(gray, (200, 64))
        normalized = resized.astype('float32') / 255.0

        # Apply adaptive thresholding
        binary = cv2.adaptiveThreshold(
            (normalized * 255).astype('uint8'),
            255,
            cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
            cv2.THRESH_BINARY,
            11, 2
        )

        # OCR prediction
        input_data = np.expand_dims(np.expand_dims(binary, 0), -1) / 255.0
        prediction = self.ocr_model.predict(input_data)

        # Decode prediction
        tag_number = self._decode_ocr_output(prediction[0])
        confidence = float(np.mean(np.max(prediction, axis=-1)))

        return tag_number, confidence

    def _decode_ocr_output(self, output: np.ndarray) -> str:
        """Decode OCR model output to string"""
        chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-'
        result = []

        for timestep in output:
            char_idx = np.argmax(timestep)
            if char_idx < len(chars):
                result.append(chars[char_idx])

        # Remove duplicates and blanks
        cleaned = []
        prev_char = None
        for char in result:
            if char != prev_char:
                cleaned.append(char)
                prev_char = char

        return ''.join(cleaned)

    def _parse_tag_format(self, tag_number: str) -> Dict:
        """Parse Bangladesh ear tag format"""
        import re

        # Expected format: BD-XXXX-XXXXXX
        pattern = r'^(BD)-(\d{4})-(\d{6})$'
        match = re.match(pattern, tag_number.upper())

        if match:
            return {
                'valid': True,
                'country_code': match.group(1),
                'farm_code': match.group(2),
                'animal_id': match.group(3)
            }

        return {
            'valid': False,
            'raw': tag_number
        }

    def export_to_tflite(self, output_dir: str):
        """Export models to TensorFlow Lite"""
        # Export detection model
        converter = tf.lite.TFLiteConverter.from_keras_model(self.detection_model)
        converter.optimizations = [tf.lite.Optimize.DEFAULT]
        tflite_detection = converter.convert()

        with open(f"{output_dir}/animal_id_detection.tflite", 'wb') as f:
            f.write(tflite_detection)

        # Export OCR model
        converter = tf.lite.TFLiteConverter.from_keras_model(self.ocr_model)
        converter.optimizations = [tf.lite.Optimize.DEFAULT]
        tflite_ocr = converter.convert()

        with open(f"{output_dir}/animal_id_ocr.tflite", 'wb') as f:
            f.write(tflite_ocr)

        _logger.info(f"Models exported to {output_dir}")
```

---

### Day 639 - Body Condition Scoring Model

**Objective:** Develop BCS model for cattle health assessment.

#### Dev 1 - Backend Lead (8h)

**Task: Body Condition Scoring Model (8h)**

```python
# ml/vision/body_condition.py
import tensorflow as tf
from tensorflow.keras import layers, models
import numpy as np
import cv2
from typing import Dict
import logging

_logger = logging.getLogger(__name__)

class BodyConditionScorer:
    """Assess cattle body condition score from images"""

    # BCS scale 1-5 (dairy cattle)
    BCS_SCALE = {
        1.0: 'Emaciated - Severe undernourishment',
        1.5: 'Very thin - Visible bones, no fat cover',
        2.0: 'Thin - Some fat cover, ribs visible',
        2.5: 'Moderately thin - Slight fat cover',
        3.0: 'Ideal - Good condition for lactating cows',
        3.5: 'Good - Adequate fat reserves',
        4.0: 'Fat - Excess condition',
        4.5: 'Very fat - Obese',
        5.0: 'Extremely fat - Health risk'
    }

    def __init__(self, model_path: str = None):
        self.model = None
        self.input_shape = (224, 224, 3)
        if model_path:
            self.load_model(model_path)

    def build_model(self):
        """Build BCS regression model"""
        base_model = tf.keras.applications.EfficientNetB0(
            input_shape=self.input_shape,
            include_top=False,
            weights='imagenet'
        )
        base_model.trainable = False

        model = models.Sequential([
            base_model,
            layers.GlobalAveragePooling2D(),
            layers.Dense(256, activation='relu'),
            layers.Dropout(0.3),
            layers.Dense(128, activation='relu'),
            layers.Dropout(0.2),
            layers.Dense(1, activation='linear')  # Regression output
        ])

        model.compile(
            optimizer=tf.keras.optimizers.Adam(learning_rate=0.001),
            loss='mse',
            metrics=['mae']
        )

        self.model = model
        return model

    def score_body_condition(self, image: np.ndarray) -> Dict:
        """Score body condition from image"""
        # Preprocess image
        processed = self._preprocess_image(image)

        # Get prediction
        raw_score = self.model.predict(np.expand_dims(processed, 0))[0][0]

        # Clamp to valid range and round to nearest 0.25
        score = max(1.0, min(5.0, raw_score))
        score = round(score * 4) / 4  # Round to nearest 0.25

        # Get description and recommendations
        description = self._get_score_description(score)
        recommendations = self._get_recommendations(score)

        return {
            'bcs_score': float(score),
            'raw_prediction': float(raw_score),
            'description': description,
            'category': self._get_category(score),
            'recommendations': recommendations,
            'confidence': self._estimate_confidence(raw_score, score)
        }

    def _preprocess_image(self, image: np.ndarray) -> np.ndarray:
        """Preprocess image for model"""
        resized = cv2.resize(image, self.input_shape[:2])
        normalized = resized.astype('float32') / 255.0
        return normalized

    def _get_score_description(self, score: float) -> str:
        """Get description for BCS score"""
        # Find closest score in scale
        closest = min(self.BCS_SCALE.keys(), key=lambda x: abs(x - score))
        return self.BCS_SCALE[closest]

    def _get_category(self, score: float) -> str:
        """Categorize BCS score"""
        if score < 2.0:
            return 'underweight'
        elif score < 2.5:
            return 'thin'
        elif score <= 3.5:
            return 'optimal'
        elif score < 4.5:
            return 'overweight'
        else:
            return 'obese'

    def _get_recommendations(self, score: float) -> list:
        """Get management recommendations based on BCS"""
        recommendations = []

        if score < 2.5:
            recommendations.extend([
                "Increase energy density of ration",
                "Check for health issues (parasites, disease)",
                "Consider additional concentrate feeding",
                "Monitor closely for improvement"
            ])
        elif score < 3.0:
            recommendations.extend([
                "Gradually increase feed intake",
                "Ensure adequate protein in diet",
                "Target BCS 3.0-3.25 before calving"
            ])
        elif score <= 3.5:
            recommendations.extend([
                "Maintain current feeding program",
                "BCS is optimal for production",
                "Continue regular monitoring"
            ])
        elif score < 4.0:
            recommendations.extend([
                "Reduce energy intake slightly",
                "Increase exercise if possible",
                "Monitor for metabolic issues"
            ])
        else:
            recommendations.extend([
                "Significantly reduce energy intake",
                "High risk for calving difficulties",
                "High risk for metabolic disorders",
                "Consult veterinarian for diet plan"
            ])

        return recommendations

    def _estimate_confidence(self, raw: float, rounded: float) -> float:
        """Estimate prediction confidence"""
        diff = abs(raw - rounded)
        return float(max(0.5, 1.0 - diff * 2))

    def export_to_tflite(self, output_path: str):
        """Export to TensorFlow Lite"""
        converter = tf.lite.TFLiteConverter.from_keras_model(self.model)
        converter.optimizations = [tf.lite.Optimize.DEFAULT]
        tflite_model = converter.convert()

        with open(output_path, 'wb') as f:
            f.write(tflite_model)

    def save_model(self, path: str):
        self.model.save(path)

    def load_model(self, path: str):
        self.model = tf.keras.models.load_model(path)
```

---

### Day 640 - Edge Deployment & UI Integration

#### Dev 2 - Full-Stack Developer (8h)

**Task: Flutter CV Integration (8h)**

```dart
// lib/services/computer_vision_service.dart
import 'dart:io';
import 'dart:typed_data';
import 'package:tflite_flutter/tflite_flutter.dart';
import 'package:image/image.dart' as img;

class ComputerVisionService {
  Interpreter? _milkQualityModel;
  Interpreter? _animalIdDetectionModel;
  Interpreter? _bcsModel;

  Future<void> loadModels() async {
    _milkQualityModel = await Interpreter.fromAsset('assets/models/milk_quality.tflite');
    _animalIdDetectionModel = await Interpreter.fromAsset('assets/models/animal_id_detection.tflite');
    _bcsModel = await Interpreter.fromAsset('assets/models/bcs_model.tflite');
  }

  Future<MilkQualityResult> assessMilkQuality(File imageFile) async {
    if (_milkQualityModel == null) {
      throw Exception('Milk quality model not loaded');
    }

    // Preprocess image
    final input = await _preprocessImage(imageFile, 224, 224);

    // Run inference
    final output = List.filled(5, 0.0).reshape([1, 5]);
    _milkQualityModel!.run(input, output);

    // Parse results
    final probabilities = output[0] as List<double>;
    final classes = ['excellent', 'good', 'acceptable', 'poor', 'reject'];
    final maxIndex = probabilities.indexWhere((p) => p == probabilities.reduce((a, b) => a > b ? a : b));

    return MilkQualityResult(
      qualityClass: classes[maxIndex],
      confidence: probabilities[maxIndex],
      probabilities: Map.fromIterables(classes, probabilities),
    );
  }

  Future<BCSResult> assessBodyCondition(File imageFile) async {
    if (_bcsModel == null) {
      throw Exception('BCS model not loaded');
    }

    final input = await _preprocessImage(imageFile, 224, 224);
    final output = List.filled(1, 0.0).reshape([1, 1]);
    _bcsModel!.run(input, output);

    final rawScore = output[0][0] as double;
    final score = (rawScore.clamp(1.0, 5.0) * 4).round() / 4;

    return BCSResult(
      score: score,
      rawScore: rawScore,
      category: _getBCSCategory(score),
    );
  }

  Future<List<List<List<double>>>> _preprocessImage(File file, int width, int height) async {
    final bytes = await file.readAsBytes();
    final image = img.decodeImage(bytes);

    if (image == null) throw Exception('Failed to decode image');

    final resized = img.copyResize(image, width: width, height: height);

    // Convert to normalized float array
    final input = List.generate(
      1,
      (_) => List.generate(
        height,
        (y) => List.generate(
          width,
          (x) {
            final pixel = resized.getPixel(x, y);
            return [
              img.getRed(pixel) / 255.0,
              img.getGreen(pixel) / 255.0,
              img.getBlue(pixel) / 255.0,
            ];
          },
        ).expand((e) => e).toList(),
      ),
    );

    return input.map((e) => e.map((r) => r as List<double>).toList()).toList();
  }

  String _getBCSCategory(double score) {
    if (score < 2.5) return 'underweight';
    if (score <= 3.5) return 'optimal';
    return 'overweight';
  }

  void dispose() {
    _milkQualityModel?.close();
    _animalIdDetectionModel?.close();
    _bcsModel?.close();
  }
}

class MilkQualityResult {
  final String qualityClass;
  final double confidence;
  final Map<String, double> probabilities;

  MilkQualityResult({
    required this.qualityClass,
    required this.confidence,
    required this.probabilities,
  });
}

class BCSResult {
  final double score;
  final double rawScore;
  final String category;

  BCSResult({
    required this.score,
    required this.rawScore,
    required this.category,
  });
}
```

---

## 4. Technical Specifications

### 4.1 Model Specifications

| Model | Architecture | Input Size | Output | Target Accuracy |
|-------|--------------|------------|--------|-----------------|
| Milk Quality | MobileNetV2 | 224x224 | 5 classes | >85% |
| Animal ID | MobileNetV2 + OCR | 224x224 | Text | >90% |
| BCS Scoring | EfficientNetB0 | 224x224 | Regression | ±0.25 |

### 4.2 Deployment Specifications

| Platform | Format | Max Size | Inference Time |
|----------|--------|----------|----------------|
| Mobile (Flutter) | TFLite | 10MB | <500ms |
| Server | TensorFlow | 50MB | <100ms |
| Edge (Raspberry Pi) | TFLite | 10MB | <1s |

---

## 5. Success Criteria Validation

- [ ] Milk quality model accuracy >85%
- [ ] Animal ID recognition >90%
- [ ] BCS scoring within ±0.25
- [ ] TFLite models exported
- [ ] Flutter integration complete
- [ ] Mobile inference <500ms

---

**End of Milestone 128 Document**
