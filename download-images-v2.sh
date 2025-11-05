#!/bin/bash

# Homepage Dummy Images Downloader v2
# Downloads quality images from multiple sources with fallbacks
# Usage: bash download-images-v2.sh

IMAGES_DIR="./dummy-images"
mkdir -p "$IMAGES_DIR"

echo "🖼️  Downloading quality dummy images..."
echo "======================================"

GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

download_image() {
    local primary_url=$1
    local fallback_url=$2
    local filename=$3
    
    echo -e "${BLUE}→ $filename${NC}"
    
    # Try primary URL
    if curl -s -L -o "$IMAGES_DIR/$filename" -w "%{http_code}" "$primary_url" | grep -q "200"; then
        if [ -s "$IMAGES_DIR/$filename" ] && [ $(stat -f%z "$IMAGES_DIR/$filename" 2>/dev/null || stat -c%s "$IMAGES_DIR/$filename") -gt 1000 ]; then
            echo -e "${GREEN}  ✓ Downloaded${NC}"
            return 0
        fi
    fi
    
    # Try fallback URL
    if [ -n "$fallback_url" ]; then
        echo -e "${YELLOW}  • Trying alternative source...${NC}"
        if curl -s -L -o "$IMAGES_DIR/$filename" "$fallback_url"; then
            echo -e "${GREEN}  ✓ Downloaded from fallback${NC}"
            return 0
        fi
    fi
    
    # Use placeholder if all fail
    echo -e "${YELLOW}  ⚠ Using placeholder${NC}"
}

# 1. HERO BLOCK
echo -e "\n${BLUE}═══════════════════════════════════${NC}"
echo -e "${BLUE}1️⃣  HERO BLOCK${NC}"
echo -e "${BLUE}═══════════════════════════════════${NC}"
download_image \
    "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1920&h=600&fit=crop&auto=format&q=80" \
    "https://picsum.photos/1920/600?random=1" \
    "1-hero-banner-1-tech.jpg"

download_image \
    "https://images.unsplash.com/photo-1460925895917-adf4e565db18?w=1920&h=600&fit=crop&auto=format&q=80" \
    "https://picsum.photos/1920/600?random=2" \
    "1-hero-banner-2-performance.jpg"

# 2. HOME STATS BLOCK
echo -e "\n${BLUE}═══════════════════════════════════${NC}"
echo -e "${BLUE}2️⃣  STATS BLOCK${NC}"
echo -e "${BLUE}═══════════════════════════════════${NC}"
download_image \
    "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200&h=600&fit=crop&auto=format&q=80" \
    "https://picsum.photos/1200/600?random=3" \
    "2-stats-analytics.jpg"

# 3. HOME TAB BLOCK
echo -e "\n${BLUE}═══════════════════════════════════${NC}"
echo -e "${BLUE}3️⃣  TAB BLOCK${NC}"
echo -e "${BLUE}═══════════════════════════════════${NC}"
download_image \
    "https://images.unsplash.com/photo-1460925895917-adf4e565db18?w=800&h=600&fit=crop&auto=format&q=80" \
    "https://picsum.photos/800/600?random=4" \
    "3-tab-1-performance.jpg"

download_image \
    "https://images.unsplash.com/photo-1563986768711-b3bef5c56dba?w=800&h=600&fit=crop&auto=format&q=80" \
    "https://picsum.photos/800/600?random=5" \
    "3-tab-2-security.jpg"

download_image \
    "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop&auto=format&q=80" \
    "https://picsum.photos/800/600?random=6" \
    "3-tab-3-scalability.jpg"

download_image \
    "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=400&fit=crop&auto=format&q=80" \
    "https://picsum.photos/400/400?random=7" \
    "3-tab-inner-icon.jpg"

download_image \
    "https://images.unsplash.com/photo-1460925895917-adf4e565db18?w=600&h=800&fit=crop&auto=format&q=80" \
    "https://picsum.photos/600/800?random=8" \
    "3-tab-mobile.jpg"

# 4. HOME FEATURES BLOCK
echo -e "\n${BLUE}═══════════════════════════════════${NC}"
echo -e "${BLUE}4️⃣  FEATURES BLOCK${NC}"
echo -e "${BLUE}═══════════════════════════════════${NC}"
download_image \
    "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=9" \
    "4-feature-1-vite.jpg"

download_image \
    "https://images.unsplash.com/photo-1561070791-2526d30994b5?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=10" \
    "4-feature-2-tailwind.jpg"

download_image \
    "https://images.unsplash.com/photo-1552664730-d307ca884978?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=11" \
    "4-feature-3-acf.jpg"

download_image \
    "https://images.unsplash.com/photo-1512941691920-25bda36dc643?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=12" \
    "4-feature-4-mobile.jpg"

download_image \
    "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=13" \
    "4-feature-5-php.jpg"

download_image \
    "https://images.unsplash.com/photo-1460925895917-adf4e565db18?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=14" \
    "4-feature-6-performance.jpg"

# 5. HOME IMAGE SLIDER BLOCK
echo -e "\n${BLUE}═══════════════════════════════════${NC}"
echo -e "${BLUE}5️⃣  SLIDER BLOCK${NC}"
echo -e "${BLUE}═══════════════════════════════════${NC}"
download_image \
    "https://images.unsplash.com/photo-1555421692-202b0bed82c9?w=800&h=600&fit=crop&auto=format&q=80" \
    "https://picsum.photos/800/600?random=15" \
    "5-project-1-ecommerce.jpg"

download_image \
    "https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&h=600&fit=crop&auto=format&q=80" \
    "https://picsum.photos/800/600?random=16" \
    "5-project-2-corporate.jpg"

download_image \
    "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop&auto=format&q=80" \
    "https://picsum.photos/800/600?random=17" \
    "5-project-3-saas.jpg"

# 6. HOME PRODUCT LISTING BLOCK
echo -e "\n${BLUE}═══════════════════════════════════${NC}"
echo -e "${BLUE}6️⃣  PRODUCT BLOCK${NC}"
echo -e "${BLUE}═══════════════════════════════════${NC}"
download_image \
    "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=18" \
    "6-product-1-theme.jpg"

download_image \
    "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=19" \
    "6-product-2-analytics.jpg"

download_image \
    "https://images.unsplash.com/photo-1563986768711-b3bef5c56dba?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=20" \
    "6-product-3-security.jpg"

download_image \
    "https://images.unsplash.com/photo-1561070791-2526d30994b5?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=21" \
    "6-product-4-design.jpg"

download_image \
    "https://images.unsplash.com/photo-1460925895917-adf4e565db18?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=22" \
    "6-product-5-performance.jpg"

download_image \
    "https://images.unsplash.com/photo-1625948515291-69613efd103f?w=100&h=100&fit=crop&auto=format&q=80" \
    "https://picsum.photos/100/100?random=23" \
    "6-product-6-hosting.jpg"

# 7. HOME CLIENT BLOCK
echo -e "\n${BLUE}═══════════════════════════════════${NC}"
echo -e "${BLUE}7️⃣  CLIENT BLOCK${NC}"
echo -e "${BLUE}═══════════════════════════════════${NC}"
download_image \
    "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop&auto=format&q=80" \
    "https://picsum.photos/300/300?random=24" \
    "7-client-1-ceo.jpg"

download_image \
    "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop&auto=format&q=80" \
    "https://picsum.photos/300/300?random=25" \
    "7-client-2-founder.jpg"

download_image \
    "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop&auto=format&q=80" \
    "https://picsum.photos/300/300?random=26" \
    "7-client-3-director.jpg"

download_image \
    "https://images.unsplash.com/photo-1502685457775-76eebde68976?w=300&h=300&fit=crop&auto=format&q=80" \
    "https://picsum.photos/300/300?random=27" \
    "7-client-4-manager.jpg"

echo -e "\n${GREEN}══════════════════════════════════════${NC}"
echo -e "${GREEN}✓ Image download complete!${NC}"
echo -e "${GREEN}══════════════════════════════════════${NC}"
echo ""
echo "📁 Location: ./dummy-images/"
echo ""
echo "Next steps:"
echo "1. Review images: open ./dummy-images/"
echo "2. Upload to WordPress Media Library"
echo "3. Use in Homepage Blocks"
echo ""
