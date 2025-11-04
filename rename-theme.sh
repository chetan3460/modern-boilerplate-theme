#!/bin/bash

# Theme Renaming Script
# Automatically renames all instances of the theme name in the codebase

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}╔════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   WordPress Theme Renaming Script     ║${NC}"
echo -e "${BLUE}╔════════════════════════════════════════╗${NC}"
echo ""

# Current theme info
CURRENT_NAME="resplast"
CURRENT_NAME_CAPS="Resplast"
CURRENT_NAME_UPPER="RESPLAST"
CURRENT_PREFIX="replast"

echo -e "${YELLOW}Current theme name:${NC} ${CURRENT_NAME}"
echo -e "${YELLOW}Current prefix:${NC} ${CURRENT_PREFIX}"
echo ""

# Get new theme info from user
read -p "Enter new theme name (lowercase, no spaces, e.g., 'acme'): " NEW_NAME

if [ -z "$NEW_NAME" ]; then
    echo -e "${RED}❌ No name provided. Exiting.${NC}"
    exit 1
fi

# Generate variations
NEW_NAME_CAPS="$(tr '[:lower:]' '[:upper:]' <<< ${NEW_NAME:0:1})${NEW_NAME:1}"
NEW_NAME_UPPER=$(echo "$NEW_NAME" | tr '[:lower:]' '[:upper:]')

echo ""
echo -e "${GREEN}New theme name will be:${NC}"
echo -e "  Lowercase: ${BLUE}${NEW_NAME}${NC}"
echo -e "  Capitalized: ${BLUE}${NEW_NAME_CAPS}${NC}"
echo -e "  Uppercase: ${BLUE}${NEW_NAME_UPPER}${NC}"
echo ""

read -p "Enter new text domain prefix (or press Enter to use '${NEW_NAME}'): " NEW_PREFIX
NEW_PREFIX=${NEW_PREFIX:-$NEW_NAME}

echo ""
echo -e "${YELLOW}⚠️  This will replace text in ALL files in this directory.${NC}"
read -p "Continue? (y/n): " CONFIRM

if [ "$CONFIRM" != "y" ]; then
    echo -e "${RED}Cancelled.${NC}"
    exit 0
fi

echo ""
echo -e "${GREEN}🚀 Starting theme rename...${NC}"
echo ""

# Create backup
BACKUP_DIR="../resplast-theme-backup-$(date +%Y%m%d-%H%M%S)"
echo -e "${BLUE}📦 Creating backup...${NC}"
cp -r . "$BACKUP_DIR"
echo -e "${GREEN}✅ Backup created at: ${BACKUP_DIR}${NC}"
echo ""

# Function to replace in files
replace_in_files() {
    local pattern=$1
    local replacement=$2
    local description=$3
    
    echo -e "${BLUE}🔄 Replacing ${description}...${NC}"
    
    # Replace in PHP files
    find . -name "*.php" -type f -not -path "*/node_modules/*" -not -path "*/vendor/*" -exec sed -i '' "s/${pattern}/${replacement}/g" {} + 2>/dev/null || true
    
    # Replace in JS files
    find . -name "*.js" -type f -not -path "*/node_modules/*" -not -path "*/dist/*" -exec sed -i '' "s/${pattern}/${replacement}/g" {} + 2>/dev/null || true
    
    # Replace in CSS files
    find . -name "*.css" -type f -not -path "*/node_modules/*" -not -path "*/dist/*" -exec sed -i '' "s/${pattern}/${replacement}/g" {} + 2>/dev/null || true
    
    # Replace in MD files
    find . -name "*.md" -type f -exec sed -i '' "s/${pattern}/${replacement}/g" {} + 2>/dev/null || true
    
    echo -e "${GREEN}  ✓ ${description} replaced${NC}"
}

# 1. Replace function prefixes
replace_in_files "${CURRENT_NAME}_" "${NEW_NAME}_" "function prefixes (${CURRENT_NAME}_ → ${NEW_NAME}_)"

# 2. Replace text domains in quotes
replace_in_files "'${CURRENT_NAME}'" "'${NEW_NAME}'" "text domains ('${CURRENT_NAME}' → '${NEW_NAME}')"
replace_in_files "\"${CURRENT_NAME}\"" "\"${NEW_NAME}\"" "text domains (double quotes)"

# 3. Replace constants
replace_in_files "${CURRENT_NAME_UPPER}_" "${NEW_NAME_UPPER}_" "constants (${CURRENT_NAME_UPPER}_ → ${NEW_NAME_UPPER}_)"

# 4. Replace theme names (capitalized)
replace_in_files "${CURRENT_NAME_CAPS} Theme" "${NEW_NAME_CAPS} Theme" "theme names"
replace_in_files "${CURRENT_NAME_CAPS}" "${NEW_NAME_CAPS}" "capitalized names"

# 5. Replace prefix
replace_in_files "T_PREFIX', '${CURRENT_PREFIX}'" "T_PREFIX', '${NEW_PREFIX}'" "T_PREFIX constant"

# 6. Replace in package.json
echo -e "${BLUE}🔄 Updating package.json...${NC}"
sed -i '' "s/\"name\": \"${CURRENT_NAME}-theme\"/\"name\": \"${NEW_NAME}-theme\"/g" package.json
sed -i '' "s/\"version\": \"[^\"]*\"/\"version\": \"1.0.0\"/g" package.json
echo -e "${GREEN}  ✓ package.json updated${NC}"

# 7. Replace in style.css
echo -e "${BLUE}🔄 Updating style.css...${NC}"
sed -i '' "s/Theme Name: ${CURRENT_NAME_CAPS}/Theme Name: ${NEW_NAME_CAPS}/g" style.css
sed -i '' "s/Text Domain: ${CURRENT_NAME}/Text Domain: ${NEW_NAME}/g" style.css
echo -e "${GREEN}  ✓ style.css updated${NC}"

# 8. Replace in manifest.json
if [ -f "manifest.json" ]; then
    echo -e "${BLUE}🔄 Updating manifest.json...${NC}"
    sed -i '' "s/\"name\": \"${CURRENT_NAME_CAPS}\"/\"name\": \"${NEW_NAME_CAPS}\"/g" manifest.json
    sed -i '' "s/\"short_name\": \"${CURRENT_NAME_CAPS}\"/\"short_name\": \"${NEW_NAME_CAPS}\"/g" manifest.json
    echo -e "${GREEN}  ✓ manifest.json updated${NC}"
fi

# 9. Update service worker cache names in generate-sw.js
if [ -f "scripts/generate-sw.js" ]; then
    echo -e "${BLUE}🔄 Updating service worker generator...${NC}"
    sed -i '' "s/const CACHE_NAME = \`${CURRENT_NAME}-v/const CACHE_NAME = \`${NEW_NAME}-v/g" scripts/generate-sw.js
    echo -e "${GREEN}  ✓ Service worker updated${NC}"
fi

# 10. Clear ACF JSON (optional)
echo ""
read -p "Clear ACF JSON field definitions? (y/n): " CLEAR_ACF
if [ "$CLEAR_ACF" == "y" ]; then
    echo -e "${BLUE}🔄 Clearing ACF JSON...${NC}"
    rm -f acf-json/*.json
    echo -e "${GREEN}  ✓ ACF JSON cleared${NC}"
fi

# 11. Clean build artifacts
echo ""
echo -e "${BLUE}🧹 Cleaning build artifacts...${NC}"
rm -rf dist node_modules/.cache 2>/dev/null || true
echo -e "${GREEN}  ✓ Build artifacts cleaned${NC}"

echo ""
echo -e "${GREEN}╔════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   ✅ Theme Renamed Successfully!       ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════╝${NC}"
echo ""
echo -e "${BLUE}Summary:${NC}"
echo -e "  Old name: ${RED}${CURRENT_NAME}${NC}"
echo -e "  New name: ${GREEN}${NEW_NAME}${NC}"
echo -e "  Old prefix: ${RED}${CURRENT_PREFIX}${NC}"
echo -e "  New prefix: ${GREEN}${NEW_PREFIX}${NC}"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo -e "  1. Review the changes (backup at: ${BACKUP_DIR})"
echo -e "  2. Run: ${BLUE}npm install${NC}"
echo -e "  3. Run: ${BLUE}npm run build${NC}"
echo -e "  4. Rename theme folder: ${BLUE}mv resplast-theme ${NEW_NAME}-theme${NC}"
echo -e "  5. Activate theme in WordPress"
echo ""
echo -e "${GREEN}🎉 Done!${NC}"
