import os
import pandas as pd
import numpy as np
from sklearn.decomposition import PCA
import json

# Load data from CSV
file_path = '../storage/app/survey_responses.csv'

# Debugging: Check if file exists
if not os.path.exists(file_path):
    print(f"File not found: {file_path}")
    exit(1)

# Try reading the file and catch any errors
try:
    data = pd.read_csv(file_path)
except Exception as e:
    print(f"Error reading CSV file: {e}")
    exit(1)

# Store response_id for later use
response_ids = data['response_id'].values
response_types = data['respondent_type'].values
response_categories = data['respondent_category'].values
information_fields = data['information_fields'].values

# Identify numeric columns dynamically
numeric_columns = [col for col in data.columns if col.startswith('answer_')]

# Convert numeric columns to numeric data types
for col in numeric_columns:
    data[col] = pd.to_numeric(data[col], errors='coerce')  # coerce to handle non-numeric values

# Drop rows with NaN values in numeric columns
data = data.dropna(subset=numeric_columns)

# Perform PCA on numeric columns
pca = PCA(n_components=2)
principalComponents = pca.fit_transform(data[numeric_columns])

# Explained variance
explained_variance = pca.explained_variance_ratio_.tolist()

# Component weights
components = pca.components_.tolist()

# Prepare result including response_id
result = pd.DataFrame(data=principalComponents, columns=['PC1', 'PC2'])
result['response_id'] = response_ids  # Use response_ids collected earlier
result['respondent_type'] = response_types  # Use response_ids collected earlier
result['respondent_category'] = response_categories  # Use response_ids collected earlier
result['information_fields'] = information_fields

# Add a small scatter effect to avoid overlapping points
result['PC1'] += np.random.normal(0, 0.01, size=result.shape[0])
result['PC2'] += np.random.normal(0, 0.01, size=result.shape[0])

# Save result to CSV
result_path = '../storage/app/pca_results.csv'
result.to_csv(result_path, index=False)

# Save explained variance, components, and headers to JSON
metadata = {
    'explained_variance': explained_variance,
    'components': components,
    'headers': numeric_columns  # Include headers in the metadata
}
with open('../storage/app/pca_metadata.json', 'w') as f:
    json.dump(metadata, f)
