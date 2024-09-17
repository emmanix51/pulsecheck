import os
import pandas as pd
import numpy as np
from sklearn.decomposition import PCA
import json

file_path = '../storage/app/survey_responses.csv'


if not os.path.exists(file_path):
    print(f"File not found: {file_path}")
    exit(1)


try:
    data = pd.read_csv(file_path)
except Exception as e:
    print(f"Error reading CSV file: {e}")
    exit(1)


response_ids = data['response_id'].values
response_types = data['respondent_type'].values
response_categories = data['respondent_category'].values
information_fields = data['information_fields'].values


numeric_columns = [col for col in data.columns if col.startswith('answer_')]


for col in numeric_columns:
    data[col] = pd.to_numeric(data[col], errors='coerce')  

# Drop rows with NaN values 
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
result['response_id'] = response_ids 
result['respondent_type'] = response_types  
result['respondent_category'] = response_categories  
result['information_fields'] = information_fields


result['PC1'] += np.random.normal(0, 0.01, size=result.shape[0])
result['PC2'] += np.random.normal(0, 0.01, size=result.shape[0])


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
