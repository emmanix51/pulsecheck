import sys
import pandas as pd
from sklearn.decomposition import PCA
import json

def perform_pca(file_path):
    # Read the CSV file
    df = pd.read_csv(file_path)

    # Perform PCA
    pca = PCA(n_components=2)
    principal_components = pca.fit_transform(df[['answer_scale']])

    # Create a DataFrame with PCA results
    pca_df = pd.DataFrame(data=principal_components, columns=['PC1', 'PC2'])
    pca_df['response_id'] = df['response_id']
    pca_df['question_id'] = df['question_id']

    # Convert the DataFrame to JSON
    result = pca_df.to_json(orient='records')
    return result

if __name__ == "__main__":
    file_path = sys.argv[1]
    pca_result = perform_pca(file_path)
    print(pca_result)
