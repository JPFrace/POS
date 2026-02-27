#!/bin/bash

#chmod +x your-script.sh
# 
# Exit immediately if a command exits with a non-zero status
set -e

# Optional: Load environment variables from .env file
if [ -f .env ]; then
  export $(grep -v '^#' .env | xargs)
fi

# Navigate to the Nuxt project directory (optional)
cd "$(dirname "$0")"

# Install dependencies (optional)
npm install

# Generate static site
npx nuxi generate

echo "Copying files to html"

rm -rf html/*

cp -r .output/public/* html

echo "✅ Nuxt site generated successfully!"