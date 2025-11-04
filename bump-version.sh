#!/bin/bash

# Bump Version Script
# Automatically updates version in package.json and service-worker.js

set -e

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}🚀 Version Bump Script${NC}"
echo ""

# Get current version from package.json
CURRENT_VERSION=$(node -p "require('./package.json').version")
echo -e "Current version: ${GREEN}${CURRENT_VERSION}${NC}"

# Ask for new version
echo ""
read -p "Enter new version (e.g., 1.0.2): " NEW_VERSION

if [ -z "$NEW_VERSION" ]; then
    echo "❌ No version provided. Exiting."
    exit 1
fi

echo ""
echo -e "${YELLOW}Updating version to ${GREEN}${NEW_VERSION}${NC}"
echo ""

# Update package.json
echo "📝 Updating package.json..."
sed -i '' "s/\"version\": \"$CURRENT_VERSION\"/\"version\": \"$NEW_VERSION\"/" package.json

# Update service-worker.js
echo "📝 Updating service-worker.js..."
sed -i '' "s/const CACHE_VERSION = '$CURRENT_VERSION'/const CACHE_VERSION = '$NEW_VERSION'/" assets/js/service-worker.js

# Build
echo ""
echo "🔨 Building assets..."
npm run build

# Copy service worker
echo "📋 Copying service worker to theme root..."
cp assets/js/service-worker.js service-worker.js

echo ""
echo -e "${GREEN}✅ Version bumped successfully!${NC}"
echo ""
echo "Next steps:"
echo "  1. Test the site locally"
echo "  2. Commit changes: git add . && git commit -m 'Bump version to $NEW_VERSION'"
echo "  3. Push to production: git push"
echo ""
