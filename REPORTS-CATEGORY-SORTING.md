# Reports Category Sorting Documentation

## Overview

This feature allows you to configure custom sorting order for reports in each category. When a user selects a category, reports are displayed according to the sort order you've configured for that specific category.

## Features

- **Per-Category Sorting**: Configure different sort orders for each report category
- **Multiple Sort Options**:
  - Newest First (Date Descending) - default
  - Oldest First (Date Ascending)
  - Title A-Z (Alphabetical Ascending)
  - Title Z-A (Alphabetical Descending)
  - Custom Order (manually set order with numbers)
- **Frontend Integration**: Sorting automatically applied when users switch categories
- **ACF Integration**: Configured via ACF fields on the Reports block

## Quick Start

1. Edit the Reports block in WordPress
2. Look for "Category Sort Order" field
3. Click "Add Category Sort"
4. Select a category and choose sort method
5. Save the block

Sorting will automatically apply when users select categories.

## Files Created/Modified

- `acf-json/group_reports_category_sort.json` - ACF field group
- `inc/reports-sorting-helper.php` - Backend sorting functions
- `templates/blocks/reports_investor_updates_cpt_block.php` - Updated block template
- `templates/partials/reports-block/report-card.php` - Updated report card
- `assets/js/reports-category-sort.js` - Frontend sorting script
- `functions.php` - Added helper file inclusion
