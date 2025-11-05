#!/bin/bash

# Homepage Dummy Images Downloader
# This script downloads relevant stock images for all homepage blocks
# Usage: bash download-dummy-images.sh

# Create directory for images
IMAGES_DIR="./dummy-images"
mkdir -p "$IMAGES_DIR"

echo "🖼️  Downloading dummy images for homepage blocks..."
echo "=================================================="

# Color codes
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to download image
download_image() {
    local url=$1
    local filename=$2
    echo -e "${BLUE}Downloading: $filename${NC}"
    curl -s -o "$IMAGES_DIR/$filename" "$url"
    if [ -f "$IMAGES_DIR/$filename" ]; then
        echo -e "${GREEN}✓ $filename${NC}"
    else
        echo -e "✗ Failed to download $filename"
    fi
}

# 1. HERO BLOCK - Banner images
echo -e "\n${BLUE}1. HERO BLOCK${NC}"
download_image "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1920&h=600&fit=crop" "1-hero-banner-1-tech.jpg"
download_image "https://images.unsplash.com/photo-1460925895917-adf4e565db18?w=1920&h=600&fit=crop" "1-hero-banner-2-performance.jpg"

# 2. HOME STATS BLOCK - Stats/Analytics
echo -e "\n${BLUE}2. HOME STATS BLOCK${NC}"
download_image "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200&h=600&fit=crop" "2-stats-analytics.jpg"

# 3. HOME TAB BLOCK - Tab images
echo -e "\n${BLUE}3. HOME TAB BLOCK${NC}"
download_image "https://images.unsplash.com/photo-1460925895917-adf4e565db18?w=800&h=600&fit=crop" "3-tab-performance-chart.jpg"
download_image "https://images.unsplash.com/photo-1563986768711-b3bef5c56dba?w=800&h=600&fit=crop" "3-tab-security-shield.jpg"
download_image "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop" "3-tab-growth-analytics.jpg"
download_image "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=400&fit=crop" "3-tab-inner-metrics.jpg"
download_image "https://images.unsplash.com/photo-1460925895917-adf4e565db18?w=600&h=800&fit=crop" "3-tab-mobile-performance.jpg"

# 4. HOME FEATURES BLOCK - Feature icons
echo -e "\n${BLUE}4. HOME FEATURES BLOCK${NC}"
download_image "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=80&h=80&fit=crop" "4-feature-vite.jpg"
download_image "https://images.unsplash.com/photo-1561070791-2526d30994b5?w=80&h=80&fit=crop" "4-feature-tailwind.jpg"
download_image "https://images.unsplash.com/photo-1552664730-d307ca884978?w=80&h=80&fit=crop" "4-feature-acf-blocks.jpg"
download_image "https://images.unsplash.com/photo-1512941691920-25bda36dc643?w=80&h=80&fit=crop" "4-feature-mobile-first.jpg"
download_image "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=80&h=80&fit=crop" "4-feature-modular-php.jpg"
download_image "https://images.unsplash.com/photo-1460925895917-adf4e565db18?w=80&h=80&fit=crop" "4-feature-performance.jpg"

# 5. HOME IMAGE SLIDER BLOCK - Project images
echo -e "\n${BLUE}5. HOME IMAGE SLIDER BLOCK${NC}"
download_image "https://images.unsplash.com/photo-1555421692-202b0bed82c9?w=800&h=600&fit=crop" "5-project-ecommerce.jpg"
download_image "https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&h=600&fit=crop" "5-project-corporate.jpg"
download_image "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop" "5-project-saas-dashboard.jpg"

# 6. HOME PRODUCT LISTING BLOCK - Product icons
echo -e "\n${BLUE}6. HOME PRODUCT LISTING BLOCK${NC}"
download_image "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=100&h=100&fit=crop" "6-product-theme.jpg"
download_image "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=100&h=100&fit=crop" "6-product-analytics.jpg"
download_image "https://images.unsplash.com/photo-1563986768711-b3bef5c56dba?w=100&h=100&fit=crop" "6-product-security.jpg"
download_image "https://images.unsplash.com/photo-1561070791-2526d30994b5?w=100&h=100&fit=crop" "6-product-design-system.jpg"
download_image "https://images.unsplash.com/photo-1460925895917-adf4e565db18?w=100&h=100&fit=crop" "6-product-performance-tools.jpg"
download_image "https://images.unsplash.com/photo-1625948515291-69613efd103f?w=100&h=100&fit=crop" "6-product-hosting.jpg"

# 7. HOME CLIENT BLOCK - Client testimonials
echo -e "\n${BLUE}7. HOME CLIENT BLOCK${NC}"
download_image "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop" "7-client-1-techcorp-ceo.jpg"
download_image "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop" "7-client-2-startup-founder.jpg"
download_image "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop" "7-client-3-marketing-director.jpg"
download_image "https://images.unsplash.com/photo-1502685457775-76eebde68976?w=300&h=300&fit=crop" "7-client-4-ecommerce-manager.jpg"

echo -e "\n${GREEN}=================================================="
echo "✓ All images downloaded successfully!"
echo "=================================================="
echo -e "\n📁 Images saved to: ${IMAGES_DIR}/"
echo -e "\nNext steps:"
echo "1. Review downloaded images in the $IMAGES_DIR folder"
echo "2. Optionally compress/convert to WebP format"
echo "3. Upload images to WordPress Media Library"
echo "4. Use them in the homepage blocks"
