# Enhanced Gallery Block ACF Structure

## Fields Layout:

```
Gallery Block
├── Hide Block (true_false)
├── Title (text) - "Our Gallery"
├── Description (wysiwyg) - Optional intro text
├── Show Tabs (true_false) - Enable/disable tab functionality
└── Gallery Categories (repeater) - The tabs and their content
    └── Category (group)
        ├── Category Name (text) - "ESG", "CSR", "Innovation"
        ├── Category Slug (text) - "esg", "csr", "innovation" (auto-generated)
        └── Gallery Items (repeater) - Images for this category
            └── Gallery Item (group)
                ├── Gallery Image (image)
                ├── Title (text) - Image caption
                └── Year (text) - When photo was taken

```

## Example Data Structure:

```json
{
  "title": "Our Sustainability Gallery",
  "description": "Explore our commitment to sustainability...",
  "show_tabs": true,
  "gallery_categories": [
    {
      "category_name": "ESG",
      "category_slug": "esg", 
      "gallery_items": [
        {
          "gallery_image": "esg-image-1.jpg",
          "title": "Green Manufacturing",
          "year": "2024"
        },
        {
          "gallery_image": "esg-image-2.jpg", 
          "title": "Renewable Energy",
          "year": "2023"
        }
      ]
    },
    {
      "category_name": "CSR",
      "category_slug": "csr",
      "gallery_items": [
        {
          "gallery_image": "csr-image-1.jpg",
          "title": "Community Outreach", 
          "year": "2024"
        },
        {
          "gallery_image": "csr-image-2.jpg",
          "title": "Education Initiative",
          "year": "2023"
        }
      ]
    }
  ]
}
```

## Benefits:
- ✅ Multiple categorized galleries per block
- ✅ Sliding tab interface like team members
- ✅ Each tab has its own image collection
- ✅ Swiper carousel within each tab
- ✅ Easy content management in WordPress admin
- ✅ No CPT complexity needed