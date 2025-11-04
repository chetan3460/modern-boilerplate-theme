#!/bin/bash
# Quick File Size Checker for Bundle Analysis
# Usage: ./check-file-sizes.sh

echo "🔍 Bundle Size Checker"
echo "======================"

echo ""
echo "📦 JAVASCRIPT BUNDLE:"
echo "--------------------"
JS_TOTAL=$(find ./assets/js -name "*.js" -exec cat {} \; | wc -c)
JS_KB=$(echo "scale=1; $JS_TOTAL/1024" | bc)
echo "Total JS: ${JS_KB} KB (${JS_TOTAL} bytes)"

echo ""
echo "Top 5 largest JS files:"
find ./assets/js -name "*.js" -exec wc -c {} \; | sort -rn | /usr/bin/head -5 | while read size file; do
    kb=$(echo "scale=1; $size/1024" | bc)
    printf "  %s KB - %s\n" "$kb" "$(basename "$file")"
done

echo ""
echo "🎨 CSS BUNDLE:"
echo "--------------"
CSS_TOTAL=$(find ./assets/css -name "*.css" -exec cat {} \; | wc -c)
CSS_KB=$(echo "scale=1; $CSS_TOTAL/1024" | bc)
echo "Total CSS: ${CSS_KB} KB (${CSS_TOTAL} bytes)"

echo ""
echo "Top 5 largest CSS files:"
find ./assets/css -name "*.css" -exec wc -c {} \; | sort -rn | /usr/bin/head -5 | while read size file; do
    kb=$(echo "scale=1; $size/1024" | bc)
    printf "  %s KB - %s\n" "$kb" "$(basename "$file")"
done

echo ""
echo "📊 PERFORMANCE BUDGET CHECK:"
echo "----------------------------"
TOTAL_KB=$(echo "scale=1; ($JS_TOTAL + $CSS_TOTAL)/1024" | bc)
JS_PERCENT=$(echo "scale=1; $JS_TOTAL/350000*100" | bc)
CSS_PERCENT=$(echo "scale=1; $CSS_TOTAL/300000*100" | bc)

printf "JS Budget:  %s KB / 350 KB (%.1f%% used) " "$JS_KB" "$JS_PERCENT"
if (( $(echo "$JS_TOTAL < 350000" | bc -l) )); then
    echo "✅ GOOD"
else
    echo "❌ OVER BUDGET"
fi

printf "CSS Budget: %s KB / 300 KB (%.1f%% used) " "$CSS_KB" "$CSS_PERCENT"
if (( $(echo "$CSS_TOTAL < 300000" | bc -l) )); then
    echo "✅ GOOD"
else
    echo "❌ OVER BUDGET"
fi

echo "Total Bundle: ${TOTAL_KB} KB"
echo ""

echo "🚀 QUICK COMPARISON:"
echo "-------------------"
echo "Your site:      ${TOTAL_KB} KB"
echo "Industry avg:   ~700 KB"
echo "Savings:        $(echo "scale=0; (700 - $TOTAL_KB)/700*100" | bc)% smaller"

if (( $(echo "$TOTAL_KB < 200" | bc -l) )); then
    echo "Status:         🏆 EXCELLENT (Top 10%)"
elif (( $(echo "$TOTAL_KB < 400" | bc -l) )); then
    echo "Status:         ✅ GOOD (Above Average)"
else
    echo "Status:         ⚠️ NEEDS OPTIMIZATION"
fi