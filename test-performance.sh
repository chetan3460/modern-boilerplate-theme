#!/bin/bash
# Quick Performance Testing Script for Resplast Theme
# Usage: ./test-performance.sh

echo "🚀 Testing Resplast Theme Performance (2025 Standards)"
echo "=================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Local development URL
LOCAL_URL="http://localhost/resplast/"

echo -e "${BLUE}Testing URL: $LOCAL_URL${NC}\n"

# Test 1: Check if site is accessible
echo -e "${YELLOW}1. Checking site accessibility...${NC}"
if curl -f -s "$LOCAL_URL" > /dev/null; then
    echo -e "${GREEN}✅ Site is accessible${NC}"
else
    echo -e "${RED}❌ Site is not accessible - check XAMPP is running${NC}"
    exit 1
fi

# Test 2: Check asset bundle sizes
echo -e "\n${YELLOW}2. Checking asset bundle sizes...${NC}"

# CSS bundle size
if [ -d "./assets/css" ]; then
    CSS_SIZE=$(find ./assets/css -name "*.css" -exec wc -c {} + 2>/dev/null | tail -1 | awk '{print $1}' || echo "0")
    CSS_SIZE_KB=$((CSS_SIZE / 1024))
    
    if [ $CSS_SIZE_KB -le 300 ]; then
        echo -e "${GREEN}✅ CSS Bundle: ${CSS_SIZE_KB}KB (≤300KB target)${NC}"
    else
        echo -e "${RED}❌ CSS Bundle: ${CSS_SIZE_KB}KB (exceeds 300KB target)${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  No CSS assets found in ./assets/css${NC}"
fi

# JS bundle size
if [ -d "./assets/js" ]; then
    JS_SIZE=$(find ./assets/js -name "*.js" -exec wc -c {} + 2>/dev/null | tail -1 | awk '{print $1}' || echo "0")
    JS_SIZE_KB=$((JS_SIZE / 1024))
    
    if [ $JS_SIZE_KB -le 350 ]; then
        echo -e "${GREEN}✅ JS Bundle: ${JS_SIZE_KB}KB (≤350KB target)${NC}"
    else
        echo -e "${RED}❌ JS Bundle: ${JS_SIZE_KB}KB (exceeds 350KB target)${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  No JS assets found in ./assets/js${NC}"
fi

# Test 3: Check for performance optimizations in HTML
echo -e "\n${YELLOW}3. Checking HTML optimizations...${NC}"

HTML_CONTENT=$(curl -s "$LOCAL_URL")

# Check critical CSS
if echo "$HTML_CONTENT" | grep -q 'id="critical-css"'; then
    echo -e "${GREEN}✅ Critical CSS is inlined${NC}"
else
    echo -e "${RED}❌ Critical CSS not found${NC}"
fi

# Check async CSS loading
if echo "$HTML_CONTENT" | grep -q 'rel="preload".*as="style"'; then
    echo -e "${GREEN}✅ Async CSS loading implemented${NC}"
else
    echo -e "${RED}❌ Async CSS loading not found${NC}"
fi

# Check font preloading
if echo "$HTML_CONTENT" | grep -q 'rel="preload".*as="font"'; then
    echo -e "${GREEN}✅ Font preloading implemented${NC}"
else
    echo -e "${YELLOW}⚠️  Font preloading not detected${NC}"
fi

# Check prefetch hints
if echo "$HTML_CONTENT" | grep -q 'rel="prefetch"'; then
    echo -e "${GREEN}✅ Prefetch hints implemented${NC}"
else
    echo -e "${YELLOW}⚠️  Prefetch hints not detected${NC}"
fi

# Check fetchpriority attribute
if echo "$HTML_CONTENT" | grep -q 'fetchpriority="high"'; then
    echo -e "${GREEN}✅ fetchpriority optimization found${NC}"
else
    echo -e "${YELLOW}⚠️  fetchpriority optimization not detected${NC}"
fi

# Check lazy loading
if echo "$HTML_CONTENT" | grep -q 'loading="lazy"'; then
    echo -e "${GREEN}✅ Lazy loading implemented${NC}"
else
    echo -e "${RED}❌ Lazy loading not found${NC}"
fi

# Test 4: Check WebP/AVIF support
echo -e "\n${YELLOW}4. Checking modern image formats...${NC}"

if echo "$HTML_CONTENT" | grep -q '<picture>'; then
    echo -e "${GREEN}✅ Picture element found (modern format support)${NC}"
else
    echo -e "${YELLOW}⚠️  Picture elements not detected${NC}"
fi

# Test 5: Check for Web Vitals monitoring
echo -e "\n${YELLOW}5. Checking Core Web Vitals monitoring...${NC}"

if echo "$HTML_CONTENT" | grep -q 'web-vitals'; then
    echo -e "${GREEN}✅ Web Vitals monitoring script found${NC}"
else
    echo -e "${RED}❌ Web Vitals monitoring not detected${NC}"
fi

# Test 6: Performance recommendations
echo -e "\n${BLUE}📊 Quick Lighthouse Test Instructions:${NC}"
echo "1. Open Chrome and go to: $LOCAL_URL"
echo "2. Open DevTools (F12 or Cmd+Opt+I)"
echo "3. Go to Lighthouse tab"
echo "4. Select Performance + Mobile"
echo "5. Click 'Analyze page load'"
echo ""
echo -e "${BLUE}🎯 Target Scores:${NC}"
echo "• Performance: ≥90"
echo "• LCP: ≤2.5s"
echo "• INP: ≤200ms"
echo "• CLS: ≤0.1"

echo -e "\n${GREEN}✨ Test complete! Check the recommendations above.${NC}"

# Test 7: Optional - Run Lighthouse CI if available
if command -v lhci >/dev/null 2>&1; then
    echo -e "\n${YELLOW}7. Running Lighthouse CI (automated)...${NC}"
    lhci autorun --config=.lighthouserc.json
else
    echo -e "\n${YELLOW}💡 To run automated Lighthouse testing:${NC}"
    echo "npm install -g @lhci/cli"
    echo "lhci autorun --config=.lighthouserc.json"
fi