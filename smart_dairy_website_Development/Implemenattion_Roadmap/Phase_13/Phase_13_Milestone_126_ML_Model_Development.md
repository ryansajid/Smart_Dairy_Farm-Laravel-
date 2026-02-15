# Milestone 126: ML Model Development

## Smart Dairy Digital Smart Portal + ERP - Phase 13: Performance & AI Optimization

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 126 of 150 (6 of 10 in Phase 13)                                       |
| **Title**        | ML Model Development                                                   |
| **Phase**        | Phase 13 - Optimization: Performance & AI                              |
| **Days**         | Days 626-630 (of 750 total)                                            |
| **Duration**     | 5 working days                                                         |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Develop and train core machine learning models for demand forecasting, milk yield prediction, and customer churn prediction with >85% accuracy, establishing the MLOps foundation for Smart Dairy's AI capabilities.

### 1.2 Objectives

1. Develop demand forecasting model using Prophet/ARIMA for product sales prediction
2. Create milk yield prediction model for individual cow production optimization
3. Build customer churn prediction model for subscription retention
4. Implement MLOps pipeline with MLflow for model versioning and tracking
5. Set up feature engineering pipeline for dairy-specific features
6. Create model validation and backtesting framework
7. Achieve >85% accuracy on all core models
8. Document model development process and methodologies

### 1.3 Key Deliverables

| Deliverable                       | Owner  | Format            | Due Day |
| --------------------------------- | ------ | ----------------- | ------- |
| Demand Forecasting Model          | Dev 1  | Python/MLflow     | 627     |
| Milk Yield Prediction Model       | Dev 1  | Python/MLflow     | 628     |
| Customer Churn Model              | Dev 1  | Python/MLflow     | 629     |
| Feature Engineering Pipeline      | Dev 2  | Python module     | 627     |
| MLflow Configuration              | Dev 2  | Docker/Config     | 626     |
| Model Training Scripts            | Dev 1  | Python scripts    | 628     |
| Model Validation Framework        | Dev 2  | Python module     | 629     |
| Model Visualization Dashboard     | Dev 3  | React components  | 630     |
| ML Documentation                  | All    | Markdown          | 630     |

### 1.4 Prerequisites

- Phase 12 complete with 6+ months historical data
- GPU infrastructure available (cloud or on-premise)
- MLflow server configured
- Data science consultation available
- Historical sales, production, and customer data accessible

### 1.5 Success Criteria

- [ ] Demand forecast MAPE <15%
- [ ] Milk yield prediction R² >0.85
- [ ] Churn prediction AUC >0.80
- [ ] Model inference time <100ms
- [ ] MLflow tracking all experiments
- [ ] Feature pipeline processing historical data
- [ ] Models validated with backtesting

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------------- |
| RFP-AI-001   | RFP    | Demand forecasting capability            | 626-627 | Demand forecast model       |
| BRD-AI-001   | BRD    | 85%+ demand forecast accuracy            | 626-627 | Model training              |
| BRD-AI-003   | BRD    | 20% reduction in inventory waste         | 626-627 | Demand forecasting          |
| SRS-ML-001   | SRS    | ML model accuracy >85%                   | 626-630 | All models                  |
| SRS-ML-002   | SRS    | Model inference time <100ms              | 629     | Model optimization          |

---

## 3. Day-by-Day Breakdown

### Day 626 - MLOps Infrastructure & Feature Engineering

**Objective:** Set up MLflow infrastructure and create feature engineering pipeline.

#### Dev 1 - Backend Lead (8h)

**Task 1: MLflow Experiment Setup (4h)**

```python
# ml/config/mlflow_config.py
import mlflow
import os
from typing import Dict, Any

class MLflowConfig:
    """MLflow configuration for Smart Dairy ML experiments"""

    TRACKING_URI = os.getenv('MLFLOW_TRACKING_URI', 'http://localhost:5000')
    EXPERIMENT_NAME = 'smart_dairy_ml'
    ARTIFACT_LOCATION = os.getenv('MLFLOW_ARTIFACT_LOCATION', './mlruns')

    @classmethod
    def setup(cls):
        """Initialize MLflow configuration"""
        mlflow.set_tracking_uri(cls.TRACKING_URI)
        mlflow.set_experiment(cls.EXPERIMENT_NAME)

    @classmethod
    def log_model_params(cls, params: Dict[str, Any]):
        """Log model parameters"""
        for key, value in params.items():
            mlflow.log_param(key, value)

    @classmethod
    def log_model_metrics(cls, metrics: Dict[str, float]):
        """Log model metrics"""
        for key, value in metrics.items():
            mlflow.log_metric(key, value)

    @classmethod
    def log_model(cls, model, model_name: str, signature=None):
        """Log trained model"""
        mlflow.sklearn.log_model(
            model,
            model_name,
            signature=signature,
            registered_model_name=f"smart_dairy_{model_name}"
        )
```

**Task 2: Base Model Classes (4h)**

```python
# ml/models/base.py
from abc import ABC, abstractmethod
from typing import Dict, Any, Optional
import pandas as pd
import numpy as np
from sklearn.model_selection import cross_val_score, TimeSeriesSplit
from sklearn.metrics import mean_absolute_percentage_error, r2_score, roc_auc_score
import mlflow
import joblib
import logging

_logger = logging.getLogger(__name__)

class BaseMLModel(ABC):
    """Base class for all Smart Dairy ML models"""

    def __init__(self, model_name: str, version: str = '1.0'):
        self.model_name = model_name
        self.version = version
        self.model = None
        self.feature_columns = []
        self.target_column = None
        self.metrics = {}

    @abstractmethod
    def preprocess(self, df: pd.DataFrame) -> pd.DataFrame:
        """Preprocess data for model"""
        pass

    @abstractmethod
    def train(self, X: pd.DataFrame, y: pd.Series) -> None:
        """Train the model"""
        pass

    @abstractmethod
    def predict(self, X: pd.DataFrame) -> np.ndarray:
        """Make predictions"""
        pass

    def evaluate(self, X: pd.DataFrame, y: pd.Series) -> Dict[str, float]:
        """Evaluate model performance"""
        predictions = self.predict(X)
        metrics = self._calculate_metrics(y, predictions)
        self.metrics = metrics
        return metrics

    @abstractmethod
    def _calculate_metrics(self, y_true: pd.Series, y_pred: np.ndarray) -> Dict[str, float]:
        """Calculate model-specific metrics"""
        pass

    def save(self, path: str):
        """Save model to file"""
        joblib.dump({
            'model': self.model,
            'feature_columns': self.feature_columns,
            'target_column': self.target_column,
            'metrics': self.metrics,
            'version': self.version
        }, path)
        _logger.info(f"Model saved to {path}")

    def load(self, path: str):
        """Load model from file"""
        data = joblib.load(path)
        self.model = data['model']
        self.feature_columns = data['feature_columns']
        self.target_column = data['target_column']
        self.metrics = data['metrics']
        self.version = data['version']
        _logger.info(f"Model loaded from {path}")

    def log_to_mlflow(self, run_name: Optional[str] = None):
        """Log model to MLflow"""
        with mlflow.start_run(run_name=run_name or self.model_name):
            mlflow.log_params({
                'model_name': self.model_name,
                'version': self.version,
                'features': str(self.feature_columns)
            })
            mlflow.log_metrics(self.metrics)
            mlflow.sklearn.log_model(
                self.model,
                self.model_name,
                registered_model_name=f"smart_dairy_{self.model_name}"
            )


class TimeSeriesModel(BaseMLModel):
    """Base class for time series models"""

    def cross_validate(self, X: pd.DataFrame, y: pd.Series, n_splits: int = 5) -> Dict[str, float]:
        """Time series cross-validation"""
        tscv = TimeSeriesSplit(n_splits=n_splits)
        scores = []

        for train_idx, test_idx in tscv.split(X):
            X_train, X_test = X.iloc[train_idx], X.iloc[test_idx]
            y_train, y_test = y.iloc[train_idx], y.iloc[test_idx]

            self.train(X_train, y_train)
            metrics = self.evaluate(X_test, y_test)
            scores.append(metrics)

        # Average metrics across folds
        avg_metrics = {}
        for key in scores[0].keys():
            avg_metrics[f'cv_{key}'] = np.mean([s[key] for s in scores])
            avg_metrics[f'cv_{key}_std'] = np.std([s[key] for s in scores])

        return avg_metrics


class ClassificationModel(BaseMLModel):
    """Base class for classification models"""

    def _calculate_metrics(self, y_true: pd.Series, y_pred: np.ndarray) -> Dict[str, float]:
        """Calculate classification metrics"""
        from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score

        # Get probability predictions if available
        if hasattr(self.model, 'predict_proba'):
            y_proba = self.model.predict_proba(self._last_X)[:, 1]
            auc = roc_auc_score(y_true, y_proba)
        else:
            auc = 0.0

        return {
            'accuracy': accuracy_score(y_true, y_pred),
            'precision': precision_score(y_true, y_pred, average='weighted'),
            'recall': recall_score(y_true, y_pred, average='weighted'),
            'f1': f1_score(y_true, y_pred, average='weighted'),
            'auc': auc
        }
```

#### Dev 2 - Full-Stack Developer (8h)

**Task 1: Feature Engineering Pipeline (4h)**

```python
# ml/features/feature_pipeline.py
import pandas as pd
import numpy as np
from typing import List, Dict, Optional
from datetime import datetime, timedelta
from sklearn.preprocessing import StandardScaler, LabelEncoder
import logging

_logger = logging.getLogger(__name__)

class FeatureEngineer:
    """Feature engineering for Smart Dairy ML models"""

    def __init__(self):
        self.scalers = {}
        self.encoders = {}

    def create_time_features(self, df: pd.DataFrame, date_column: str) -> pd.DataFrame:
        """Create time-based features"""
        df = df.copy()
        df[date_column] = pd.to_datetime(df[date_column])

        # Basic time features
        df['year'] = df[date_column].dt.year
        df['month'] = df[date_column].dt.month
        df['day'] = df[date_column].dt.day
        df['day_of_week'] = df[date_column].dt.dayofweek
        df['day_of_year'] = df[date_column].dt.dayofyear
        df['week_of_year'] = df[date_column].dt.isocalendar().week
        df['quarter'] = df[date_column].dt.quarter

        # Cyclical encoding for seasonal patterns
        df['month_sin'] = np.sin(2 * np.pi * df['month'] / 12)
        df['month_cos'] = np.cos(2 * np.pi * df['month'] / 12)
        df['day_of_week_sin'] = np.sin(2 * np.pi * df['day_of_week'] / 7)
        df['day_of_week_cos'] = np.cos(2 * np.pi * df['day_of_week'] / 7)

        # Bangladesh-specific features
        df['is_weekend'] = df['day_of_week'].isin([4, 5]).astype(int)  # Friday-Saturday
        df['is_ramadan'] = self._is_ramadan(df[date_column]).astype(int)
        df['is_eid'] = self._is_eid(df[date_column]).astype(int)

        return df

    def create_lag_features(self, df: pd.DataFrame, target_column: str,
                           lags: List[int] = [1, 7, 14, 30]) -> pd.DataFrame:
        """Create lag features for time series"""
        df = df.copy()

        for lag in lags:
            df[f'{target_column}_lag_{lag}'] = df[target_column].shift(lag)

        # Rolling statistics
        for window in [7, 14, 30]:
            df[f'{target_column}_rolling_mean_{window}'] = (
                df[target_column].rolling(window=window).mean()
            )
            df[f'{target_column}_rolling_std_{window}'] = (
                df[target_column].rolling(window=window).std()
            )
            df[f'{target_column}_rolling_min_{window}'] = (
                df[target_column].rolling(window=window).min()
            )
            df[f'{target_column}_rolling_max_{window}'] = (
                df[target_column].rolling(window=window).max()
            )

        return df

    def create_dairy_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """Create dairy-specific features"""
        df = df.copy()

        # Milk production features (if applicable)
        if 'lactation_stage' in df.columns:
            df['early_lactation'] = (df['lactation_stage'] <= 60).astype(int)
            df['peak_lactation'] = ((df['lactation_stage'] > 60) & (df['lactation_stage'] <= 120)).astype(int)
            df['late_lactation'] = (df['lactation_stage'] > 120).astype(int)

        # Animal age features
        if 'age_months' in df.columns:
            df['is_heifer'] = (df['age_months'] < 24).astype(int)
            df['is_mature'] = ((df['age_months'] >= 24) & (df['age_months'] <= 84)).astype(int)
            df['is_old'] = (df['age_months'] > 84).astype(int)

        # Seasonal dairy patterns
        if 'month' in df.columns:
            # Bangladesh: Peak production typically Nov-Feb (winter)
            df['is_peak_season'] = df['month'].isin([11, 12, 1, 2]).astype(int)
            # Low production during monsoon (Jun-Sep)
            df['is_monsoon'] = df['month'].isin([6, 7, 8, 9]).astype(int)

        return df

    def _is_ramadan(self, dates: pd.Series) -> pd.Series:
        """Check if date falls in Ramadan (approximate)"""
        # This is a simplified check - actual dates vary by lunar calendar
        # In production, use a proper Islamic calendar library
        return pd.Series([False] * len(dates))

    def _is_eid(self, dates: pd.Series) -> pd.Series:
        """Check if date is Eid holiday"""
        return pd.Series([False] * len(dates))

    def scale_features(self, df: pd.DataFrame, columns: List[str],
                       fit: bool = True) -> pd.DataFrame:
        """Scale numerical features"""
        df = df.copy()

        for col in columns:
            if fit:
                self.scalers[col] = StandardScaler()
                df[col] = self.scalers[col].fit_transform(df[[col]])
            else:
                if col in self.scalers:
                    df[col] = self.scalers[col].transform(df[[col]])

        return df

    def encode_categorical(self, df: pd.DataFrame, columns: List[str],
                          fit: bool = True) -> pd.DataFrame:
        """Encode categorical features"""
        df = df.copy()

        for col in columns:
            if fit:
                self.encoders[col] = LabelEncoder()
                df[col] = self.encoders[col].fit_transform(df[col].astype(str))
            else:
                if col in self.encoders:
                    df[col] = self.encoders[col].transform(df[col].astype(str))

        return df
```

**Task 2: MLflow Infrastructure Setup (4h)**

```yaml
# docker-compose.mlflow.yml
version: '3.8'

services:
  mlflow-server:
    image: ghcr.io/mlflow/mlflow:v2.9.0
    container_name: smart-dairy-mlflow
    ports:
      - "5000:5000"
    environment:
      - MLFLOW_BACKEND_STORE_URI=postgresql://mlflow:${MLFLOW_DB_PASSWORD}@postgres:5432/mlflow
      - MLFLOW_DEFAULT_ARTIFACT_ROOT=s3://smart-dairy-mlflow/artifacts
      - AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID}
      - AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY}
    command: >
      mlflow server
      --backend-store-uri postgresql://mlflow:${MLFLOW_DB_PASSWORD}@postgres:5432/mlflow
      --default-artifact-root s3://smart-dairy-mlflow/artifacts
      --host 0.0.0.0
      --port 5000
    depends_on:
      - postgres
    restart: unless-stopped

  mlflow-db:
    image: postgres:16-alpine
    container_name: smart-dairy-mlflow-db
    environment:
      - POSTGRES_USER=mlflow
      - POSTGRES_PASSWORD=${MLFLOW_DB_PASSWORD}
      - POSTGRES_DB=mlflow
    volumes:
      - mlflow_db_data:/var/lib/postgresql/data
    restart: unless-stopped

volumes:
  mlflow_db_data:
```

---

### Day 627 - Demand Forecasting Model

**Objective:** Develop and train demand forecasting model for product sales prediction.

#### Dev 1 - Backend Lead (8h)

**Task: Demand Forecasting Model (8h)**

```python
# ml/models/demand_forecast.py
import pandas as pd
import numpy as np
from prophet import Prophet
from sklearn.ensemble import GradientBoostingRegressor
from typing import Dict, List, Optional
import mlflow
from .base import TimeSeriesModel
from ..features.feature_pipeline import FeatureEngineer
import logging

_logger = logging.getLogger(__name__)

class DemandForecastModel(TimeSeriesModel):
    """Demand forecasting model for Smart Dairy products"""

    def __init__(self, product_id: Optional[int] = None):
        super().__init__('demand_forecast', version='1.0')
        self.product_id = product_id
        self.feature_engineer = FeatureEngineer()
        self.prophet_model = None
        self.ensemble_model = None

    def preprocess(self, df: pd.DataFrame) -> pd.DataFrame:
        """Preprocess sales data for forecasting"""
        df = df.copy()

        # Ensure required columns
        required_cols = ['date', 'quantity']
        for col in required_cols:
            if col not in df.columns:
                raise ValueError(f"Missing required column: {col}")

        # Aggregate daily sales
        df['date'] = pd.to_datetime(df['date'])
        daily_sales = df.groupby('date').agg({
            'quantity': 'sum',
            'revenue': 'sum' if 'revenue' in df.columns else lambda x: 0
        }).reset_index()

        # Fill missing dates
        date_range = pd.date_range(
            start=daily_sales['date'].min(),
            end=daily_sales['date'].max(),
            freq='D'
        )
        daily_sales = daily_sales.set_index('date').reindex(date_range, fill_value=0).reset_index()
        daily_sales.columns = ['date', 'quantity', 'revenue']

        # Create features
        daily_sales = self.feature_engineer.create_time_features(daily_sales, 'date')
        daily_sales = self.feature_engineer.create_lag_features(daily_sales, 'quantity')
        daily_sales = self.feature_engineer.create_dairy_features(daily_sales)

        # Drop rows with NaN from lag features
        daily_sales = daily_sales.dropna()

        return daily_sales

    def train(self, X: pd.DataFrame, y: pd.Series) -> None:
        """Train demand forecasting model"""
        _logger.info("Training demand forecast model...")

        # Train Prophet model
        prophet_df = pd.DataFrame({
            'ds': X['date'] if 'date' in X.columns else X.index,
            'y': y
        })

        self.prophet_model = Prophet(
            yearly_seasonality=True,
            weekly_seasonality=True,
            daily_seasonality=False,
            seasonality_mode='multiplicative',
            changepoint_prior_scale=0.05
        )

        # Add Bangladesh-specific seasonality
        self.prophet_model.add_seasonality(
            name='monthly',
            period=30.5,
            fourier_order=5
        )

        self.prophet_model.fit(prophet_df)

        # Train ensemble model for residuals
        feature_cols = [col for col in X.columns if col not in ['date', 'quantity']]
        self.feature_columns = feature_cols

        prophet_pred = self.prophet_model.predict(prophet_df)['yhat'].values
        residuals = y.values - prophet_pred

        self.ensemble_model = GradientBoostingRegressor(
            n_estimators=100,
            max_depth=5,
            learning_rate=0.1,
            random_state=42
        )
        self.ensemble_model.fit(X[feature_cols], residuals)

        self.model = {
            'prophet': self.prophet_model,
            'ensemble': self.ensemble_model
        }

        _logger.info("Demand forecast model training complete")

    def predict(self, X: pd.DataFrame) -> np.ndarray:
        """Make demand predictions"""
        # Prophet predictions
        prophet_df = pd.DataFrame({
            'ds': X['date'] if 'date' in X.columns else X.index
        })
        prophet_pred = self.prophet_model.predict(prophet_df)['yhat'].values

        # Ensemble predictions for residuals
        feature_cols = self.feature_columns
        ensemble_pred = self.ensemble_model.predict(X[feature_cols])

        # Combine predictions
        final_pred = prophet_pred + ensemble_pred

        # Ensure non-negative predictions
        final_pred = np.maximum(final_pred, 0)

        return final_pred

    def forecast_future(self, periods: int = 30) -> pd.DataFrame:
        """Forecast future demand"""
        future = self.prophet_model.make_future_dataframe(periods=periods)
        forecast = self.prophet_model.predict(future)

        return forecast[['ds', 'yhat', 'yhat_lower', 'yhat_upper']].tail(periods)

    def _calculate_metrics(self, y_true: pd.Series, y_pred: np.ndarray) -> Dict[str, float]:
        """Calculate forecasting metrics"""
        from sklearn.metrics import mean_absolute_error, mean_squared_error

        mape = np.mean(np.abs((y_true - y_pred) / np.maximum(y_true, 1))) * 100
        mae = mean_absolute_error(y_true, y_pred)
        rmse = np.sqrt(mean_squared_error(y_true, y_pred))

        return {
            'mape': mape,
            'mae': mae,
            'rmse': rmse,
            'r2': 1 - (np.sum((y_true - y_pred) ** 2) / np.sum((y_true - np.mean(y_true)) ** 2))
        }
```

---

### Day 628 - Milk Yield Prediction Model

**Objective:** Develop model to predict individual cow milk production.

#### Dev 1 - Backend Lead (8h)

**Task: Milk Yield Prediction Model (8h)**

```python
# ml/models/yield_prediction.py
import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestRegressor, GradientBoostingRegressor
from sklearn.model_selection import train_test_split
from typing import Dict, Optional
import mlflow
from .base import BaseMLModel
from ..features.feature_pipeline import FeatureEngineer
import logging

_logger = logging.getLogger(__name__)

class MilkYieldPredictionModel(BaseMLModel):
    """Predict individual cow milk yield based on various factors"""

    def __init__(self):
        super().__init__('milk_yield_prediction', version='1.0')
        self.feature_engineer = FeatureEngineer()

    def preprocess(self, df: pd.DataFrame) -> pd.DataFrame:
        """Preprocess milk production data"""
        df = df.copy()

        required_cols = ['animal_id', 'record_date', 'total_yield']
        for col in required_cols:
            if col not in df.columns:
                raise ValueError(f"Missing required column: {col}")

        # Create time features
        df = self.feature_engineer.create_time_features(df, 'record_date')

        # Create dairy-specific features
        df = self.feature_engineer.create_dairy_features(df)

        # Lactation curve features
        if 'days_in_milk' in df.columns:
            # Wood's lactation curve parameters
            df['log_dim'] = np.log(df['days_in_milk'] + 1)
            df['dim_squared'] = df['days_in_milk'] ** 2

        # Create lag features for yield
        df = df.sort_values(['animal_id', 'record_date'])
        for lag in [1, 7, 14]:
            df[f'yield_lag_{lag}'] = df.groupby('animal_id')['total_yield'].shift(lag)

        # Rolling statistics per animal
        for window in [7, 14]:
            df[f'yield_rolling_mean_{window}'] = (
                df.groupby('animal_id')['total_yield']
                .transform(lambda x: x.rolling(window, min_periods=1).mean())
            )

        # Health indicators
        if 'health_score' in df.columns:
            df['is_healthy'] = (df['health_score'] >= 80).astype(int)

        # Environmental features
        if 'temperature' in df.columns:
            df['is_heat_stress'] = (df['temperature'] > 30).astype(int)

        return df.dropna()

    def train(self, X: pd.DataFrame, y: pd.Series) -> None:
        """Train milk yield prediction model"""
        _logger.info("Training milk yield prediction model...")

        # Define feature columns
        exclude_cols = ['animal_id', 'record_date', 'total_yield', 'date']
        self.feature_columns = [col for col in X.columns if col not in exclude_cols]

        X_features = X[self.feature_columns]

        # Train ensemble of models
        self.model = GradientBoostingRegressor(
            n_estimators=200,
            max_depth=6,
            learning_rate=0.1,
            subsample=0.8,
            random_state=42
        )
        self.model.fit(X_features, y)

        # Feature importance
        self.feature_importance = dict(zip(
            self.feature_columns,
            self.model.feature_importances_
        ))

        _logger.info("Milk yield prediction model training complete")
        _logger.info(f"Top features: {sorted(self.feature_importance.items(), key=lambda x: x[1], reverse=True)[:5]}")

    def predict(self, X: pd.DataFrame) -> np.ndarray:
        """Predict milk yield"""
        X_features = X[self.feature_columns]
        predictions = self.model.predict(X_features)
        return np.maximum(predictions, 0)  # Ensure non-negative

    def predict_for_animal(self, animal_id: int, days_ahead: int = 7,
                          historical_data: pd.DataFrame = None) -> pd.DataFrame:
        """Predict yield for a specific animal"""
        if historical_data is None:
            raise ValueError("Historical data required for prediction")

        animal_data = historical_data[historical_data['animal_id'] == animal_id].copy()
        if animal_data.empty:
            raise ValueError(f"No data found for animal {animal_id}")

        # Generate future dates
        last_date = animal_data['record_date'].max()
        future_dates = pd.date_range(start=last_date + pd.Timedelta(days=1), periods=days_ahead)

        predictions = []
        for future_date in future_dates:
            # Create feature row
            future_row = self._create_future_features(animal_data, future_date)
            pred = self.predict(pd.DataFrame([future_row]))[0]
            predictions.append({
                'date': future_date,
                'predicted_yield': pred
            })

        return pd.DataFrame(predictions)

    def _create_future_features(self, historical_data: pd.DataFrame,
                                target_date: pd.Timestamp) -> Dict:
        """Create feature row for future date"""
        # Implementation depends on available features
        return {}

    def _calculate_metrics(self, y_true: pd.Series, y_pred: np.ndarray) -> Dict[str, float]:
        """Calculate regression metrics"""
        from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score

        return {
            'mae': mean_absolute_error(y_true, y_pred),
            'rmse': np.sqrt(mean_squared_error(y_true, y_pred)),
            'r2': r2_score(y_true, y_pred),
            'mape': np.mean(np.abs((y_true - y_pred) / np.maximum(y_true, 0.1))) * 100
        }
```

---

### Day 629 - Customer Churn Prediction Model

**Objective:** Develop model to predict subscription customer churn.

#### Dev 1 - Backend Lead (8h)

**Task: Churn Prediction Model (8h)**

```python
# ml/models/churn_prediction.py
import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestClassifier, GradientBoostingClassifier
from sklearn.preprocessing import StandardScaler
from sklearn.model_selection import train_test_split
from imblearn.over_sampling import SMOTE
from typing import Dict, List, Tuple
import mlflow
from .base import ClassificationModel
import logging

_logger = logging.getLogger(__name__)

class ChurnPredictionModel(ClassificationModel):
    """Predict customer subscription churn"""

    def __init__(self):
        super().__init__('churn_prediction', version='1.0')
        self.scaler = StandardScaler()
        self.smote = SMOTE(random_state=42)

    def preprocess(self, df: pd.DataFrame) -> pd.DataFrame:
        """Preprocess customer data for churn prediction"""
        df = df.copy()

        # RFM Features (Recency, Frequency, Monetary)
        if 'last_order_date' in df.columns:
            df['recency_days'] = (pd.Timestamp.now() - pd.to_datetime(df['last_order_date'])).dt.days

        if 'order_count' in df.columns:
            df['frequency'] = df['order_count']

        if 'total_spent' in df.columns:
            df['monetary'] = df['total_spent']

        # Subscription features
        if 'subscription_start_date' in df.columns:
            df['subscription_age_days'] = (
                pd.Timestamp.now() - pd.to_datetime(df['subscription_start_date'])
            ).dt.days

        if 'subscription_plan' in df.columns:
            df = pd.get_dummies(df, columns=['subscription_plan'], prefix='plan')

        # Engagement features
        if 'login_count_30d' in df.columns:
            df['engagement_score'] = (
                df['login_count_30d'] * 0.3 +
                df.get('order_count_30d', 0) * 0.5 +
                df.get('support_tickets_30d', 0) * -0.2
            )

        # Payment behavior
        if 'payment_failures' in df.columns:
            df['has_payment_issues'] = (df['payment_failures'] > 0).astype(int)

        # Delivery satisfaction
        if 'avg_delivery_rating' in df.columns:
            df['low_satisfaction'] = (df['avg_delivery_rating'] < 3.5).astype(int)

        return df

    def train(self, X: pd.DataFrame, y: pd.Series) -> None:
        """Train churn prediction model"""
        _logger.info("Training churn prediction model...")

        # Define feature columns
        exclude_cols = ['customer_id', 'churned', 'churn_date']
        self.feature_columns = [col for col in X.columns if col not in exclude_cols]

        X_features = X[self.feature_columns]

        # Scale features
        X_scaled = self.scaler.fit_transform(X_features)

        # Handle class imbalance with SMOTE
        X_resampled, y_resampled = self.smote.fit_resample(X_scaled, y)

        # Train model
        self.model = GradientBoostingClassifier(
            n_estimators=150,
            max_depth=5,
            learning_rate=0.1,
            subsample=0.8,
            random_state=42
        )
        self.model.fit(X_resampled, y_resampled)

        # Store for prediction
        self._last_X = X_scaled

        # Feature importance
        self.feature_importance = dict(zip(
            self.feature_columns,
            self.model.feature_importances_
        ))

        _logger.info("Churn prediction model training complete")

    def predict(self, X: pd.DataFrame) -> np.ndarray:
        """Predict churn"""
        X_features = X[self.feature_columns]
        X_scaled = self.scaler.transform(X_features)
        self._last_X = X_scaled
        return self.model.predict(X_scaled)

    def predict_proba(self, X: pd.DataFrame) -> np.ndarray:
        """Predict churn probability"""
        X_features = X[self.feature_columns]
        X_scaled = self.scaler.transform(X_features)
        return self.model.predict_proba(X_scaled)[:, 1]

    def get_at_risk_customers(self, X: pd.DataFrame,
                               threshold: float = 0.5) -> pd.DataFrame:
        """Identify customers at risk of churning"""
        proba = self.predict_proba(X)

        at_risk = X.copy()
        at_risk['churn_probability'] = proba
        at_risk['risk_level'] = pd.cut(
            proba,
            bins=[0, 0.3, 0.5, 0.7, 1.0],
            labels=['Low', 'Medium', 'High', 'Critical']
        )

        return at_risk[at_risk['churn_probability'] >= threshold].sort_values(
            'churn_probability', ascending=False
        )

    def get_retention_recommendations(self, customer_data: pd.Series) -> List[str]:
        """Generate retention recommendations for a customer"""
        recommendations = []

        if customer_data.get('recency_days', 0) > 30:
            recommendations.append("Send re-engagement email campaign")

        if customer_data.get('has_payment_issues', 0):
            recommendations.append("Offer alternative payment methods")

        if customer_data.get('low_satisfaction', 0):
            recommendations.append("Schedule customer feedback call")

        if customer_data.get('engagement_score', 1) < 0.3:
            recommendations.append("Offer loyalty discount or free delivery")

        return recommendations
```

---

### Day 630 - Model Visualization & Documentation

#### Dev 3 - Frontend Lead (8h)

**Task: Model Visualization Dashboard (8h)**

```tsx
// frontend/src/pages/ml/ModelDashboard.tsx
import React from 'react';
import { Card, Row, Col, Statistic, Table, Tag, Tabs } from 'antd';
import { Line, Column, Pie } from '@ant-design/plots';
import { useQuery } from '@tanstack/react-query';

interface ModelMetrics {
  name: string;
  version: string;
  accuracy: number;
  lastTrained: string;
  status: 'active' | 'training' | 'inactive';
}

const ModelDashboard: React.FC = () => {
  const { data: models } = useQuery<ModelMetrics[]>({
    queryKey: ['ml-models'],
    queryFn: () => fetch('/api/v1/ml/models').then(r => r.json())
  });

  const { data: demandForecast } = useQuery({
    queryKey: ['demand-forecast'],
    queryFn: () => fetch('/api/v1/ml/demand-forecast/predictions').then(r => r.json())
  });

  const { data: yieldPredictions } = useQuery({
    queryKey: ['yield-predictions'],
    queryFn: () => fetch('/api/v1/ml/yield/predictions').then(r => r.json())
  });

  const { data: churnRisk } = useQuery({
    queryKey: ['churn-risk'],
    queryFn: () => fetch('/api/v1/ml/churn/at-risk').then(r => r.json())
  });

  const modelColumns = [
    { title: 'Model', dataIndex: 'name', key: 'name' },
    { title: 'Version', dataIndex: 'version', key: 'version' },
    {
      title: 'Accuracy',
      dataIndex: 'accuracy',
      key: 'accuracy',
      render: (v: number) => `${(v * 100).toFixed(1)}%`
    },
    {
      title: 'Status',
      dataIndex: 'status',
      key: 'status',
      render: (status: string) => (
        <Tag color={status === 'active' ? 'green' : status === 'training' ? 'orange' : 'red'}>
          {status.toUpperCase()}
        </Tag>
      )
    },
    { title: 'Last Trained', dataIndex: 'lastTrained', key: 'lastTrained' }
  ];

  return (
    <div className="ml-dashboard">
      <h1>ML Model Dashboard</h1>

      <Row gutter={16}>
        <Col span={8}>
          <Card>
            <Statistic
              title="Demand Forecast MAPE"
              value={demandForecast?.metrics?.mape || 0}
              suffix="%"
              precision={1}
              valueStyle={{ color: (demandForecast?.metrics?.mape || 100) < 15 ? '#3f8600' : '#cf1322' }}
            />
          </Card>
        </Col>
        <Col span={8}>
          <Card>
            <Statistic
              title="Yield Prediction R²"
              value={yieldPredictions?.metrics?.r2 || 0}
              precision={3}
              valueStyle={{ color: (yieldPredictions?.metrics?.r2 || 0) > 0.85 ? '#3f8600' : '#cf1322' }}
            />
          </Card>
        </Col>
        <Col span={8}>
          <Card>
            <Statistic
              title="Churn Model AUC"
              value={churnRisk?.metrics?.auc || 0}
              precision={3}
              valueStyle={{ color: (churnRisk?.metrics?.auc || 0) > 0.8 ? '#3f8600' : '#cf1322' }}
            />
          </Card>
        </Col>
      </Row>

      <Card title="Model Registry" style={{ marginTop: 16 }}>
        <Table
          columns={modelColumns}
          dataSource={models || []}
          rowKey="name"
          pagination={false}
        />
      </Card>

      <Tabs style={{ marginTop: 16 }}>
        <Tabs.TabPane tab="Demand Forecast" key="demand">
          <Card title="30-Day Demand Forecast">
            <Line
              data={demandForecast?.predictions || []}
              xField="date"
              yField="predicted_demand"
              smooth
              point={{ size: 3 }}
            />
          </Card>
        </Tabs.TabPane>

        <Tabs.TabPane tab="At-Risk Customers" key="churn">
          <Card title="Customers at Risk of Churning">
            <Table
              columns={[
                { title: 'Customer', dataIndex: 'customer_name' },
                { title: 'Churn Probability', dataIndex: 'churn_probability',
                  render: (v: number) => `${(v * 100).toFixed(1)}%` },
                { title: 'Risk Level', dataIndex: 'risk_level',
                  render: (level: string) => (
                    <Tag color={level === 'Critical' ? 'red' : level === 'High' ? 'orange' : 'yellow'}>
                      {level}
                    </Tag>
                  )
                }
              ]}
              dataSource={churnRisk?.customers || []}
              rowKey="customer_id"
            />
          </Card>
        </Tabs.TabPane>
      </Tabs>
    </div>
  );
};

export default ModelDashboard;
```

---

## 4. Technical Specifications

### 4.1 Model Specifications

| Model | Algorithm | Target Accuracy | Training Data |
|-------|-----------|-----------------|---------------|
| Demand Forecast | Prophet + GBM | MAPE <15% | 6+ months sales |
| Yield Prediction | GBM | R² >0.85 | 6+ months production |
| Churn Prediction | GBM + SMOTE | AUC >0.80 | Customer history |

### 4.2 Infrastructure Requirements

| Component | Specification |
|-----------|---------------|
| GPU | NVIDIA T4 or better |
| RAM | 32GB minimum |
| Storage | 100GB for models/data |
| MLflow | PostgreSQL backend |

---

## 5. Success Criteria Validation

- [ ] Demand forecast MAPE <15%
- [ ] Yield prediction R² >0.85
- [ ] Churn prediction AUC >0.80
- [ ] All models logged in MLflow
- [ ] Model inference <100ms
- [ ] Documentation complete

---

**End of Milestone 126 Document**
