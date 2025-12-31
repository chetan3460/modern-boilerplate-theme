# AI Prompt Templates for ACF Block Development

This document contains ready-to-use AI prompt templates for generating WordPress ACF blocks. Each prompt follows a structured format with bracketed placeholders that you can customize for your specific needs.

## Table of Contents

1. [Hero Banner Block](#1-hero-banner-block-prompt)
2. [Features Grid Block](#2-features-grid-block-prompt)
3. [CTA (Call to Action) Block](#3-cta-call-to-action-block-prompt)
4. [Accordion Block](#4-accordion-block-prompt)
5. [Team Members Block](#5-team-members-block-prompt)
6. [Image Gallery Block](#6-image-gallery-block-prompt)
7. [Statistics/Counter Block](#7-statisticscounter-block-prompt)
8. [News/Blog Listing Block](#8-newsblog-listing-block-prompt)
9. [Contact Form Block](#9-contact-form-block-prompt)
10. [Testimonials Block](#10-testimonials-block-prompt)
11. [Services Block](#11-services-block-prompt)
12. [Pricing Table Block](#12-pricing-table-block-prompt)
13. [Video Block](#13-video-block-prompt)
14. [Timeline Block](#14-timeline-block-prompt)
15. [Logo Carousel Block](#15-logo-carousel-block-prompt)

---

## 1. Hero Banner Block Prompt

```
I am working on a WordPress theme called [theme_name] using [ACF_flexible_content]. I need to create a [hero_banner_block] that displays a hero section with the following features:

- A responsive image slider with [number_of_slides] slides
- Each slide should have [title], [subtitle], and [description] fields
- Support for [CTA_button] with customizable link and text
- A spotlight/news section on the right side showing [number_of_news_items] recent posts
- The block should use [Swiper.js] for the slider functionality
- Implement lazy loading for images after the first slide
- Include a [hide_block] toggle option
- Style with [Tailwind_CSS] classes
- Follow WordPress coding standards with proper escaping

Can you generate the PHP template code for this ACF block?
```

**Example Usage:**

```
I am working on a WordPress theme called resplast-theme using ACF flexible content. I need to create a hero banner block that displays a hero section with the following features:

- A responsive image slider with 5 slides
- Each slide should have title, subtitle, and description fields
- Support for CTA button with customizable link and text
- A spotlight/news section on the right side showing 3 recent posts
- The block should use Swiper.js for the slider functionality
- Implement lazy loading for images after the first slide
- Include a hide_block toggle option
- Style with Tailwind CSS classes
- Follow WordPress coding standards with proper escaping

Can you generate the PHP template code for this ACF block?
```

---

## 2. Features Grid Block Prompt

```
I am developing a WordPress website for [project_name] using [ACF_Pro]. I need to create a [features_grid_block] that can display feature items in a responsive grid layout.

Requirements:
- Support [repeater_field] for multiple feature items
- Each item should have [icon], [title], and [content] fields
- Auto-layout: [3_columns] for 1-3 items, and [3_top_2_bottom] for 4+ items
- Add [fade_animation] with staggered delays
- Include [title] and [description] fields at the top
- Use [Tailwind_CSS] rounded corners and sky-blue background
- Add a [hide_block] option
- Include decorative shape elements
- Make it responsive for mobile, tablet, and desktop

Generate the complete PHP block template following WordPress best practices.
```

**Example Usage:**

```
I am developing a WordPress website for Resplast using ACF Pro. I need to create a global features block that can display feature items in a responsive grid layout.

Requirements:
- Support repeater field for multiple feature items
- Each item should have icon, title, and content fields
- Auto-layout: 3 columns for 1-3 items, and 3 top 2 bottom for 4+ items
- Add fade-up animation with staggered delays
- Include title and description fields at the top
- Use Tailwind CSS rounded corners and sky-blue background
- Add a hide_block option
- Include decorative shape elements
- Make it responsive for mobile, tablet, and desktop

Generate the complete PHP block template following WordPress best practices.
```

---

## 3. CTA (Call to Action) Block Prompt

```
I need to create a [global_cta_block] for my WordPress theme [theme_name] using [ACF_flexible_content]. The block should include:

- [heading] text field
- [subheading] or description field
- [button_group] with primary and secondary CTA buttons
- Optional [background_image] field
- [background_color] picker option
- [text_alignment] option (left, center, right)
- Support for [custom_CSS_classes]
- [hide_block] toggle functionality
- Responsive design using [Tailwind_CSS]
- Proper WordPress escaping functions

Create the PHP template file with all ACF field retrievals and HTML structure.
```

**Example Usage:**

```
I need to create a global CTA block for my WordPress theme resplast-theme using ACF flexible content. The block should include:

- heading text field
- subheading or description field
- button group with primary and secondary CTA buttons
- Optional background image field
- background color picker option
- text alignment option (left, center, right)
- Support for custom CSS classes
- hide_block toggle functionality
- Responsive design using Tailwind CSS
- Proper WordPress escaping functions

Create the PHP template file with all ACF field retrievals and HTML structure.
```

---

## 4. Accordion Block Prompt

```
I am building a [FAQ_accordion_block] for [website_name] using [ACF_repeater_fields]. Requirements:

- Support multiple accordion items using [repeater_field]
- Each item has [question] and [answer] fields
- Implement [Alpine.js] for toggle functionality
- Add smooth [transition_animations]
- Style with [Tailwind_CSS] with rounded corners and shadows
- Include [expand/collapse_icons]
- Optional [default_open] setting for first item
- [hide_block] toggle option
- Accessible ARIA attributes
- Mobile-responsive design

Generate the complete ACF block PHP template code.
```

**Example Usage:**

```
I am building an FAQ accordion block for Resplast using ACF repeater fields. Requirements:

- Support multiple accordion items using repeater field
- Each item has question and answer fields
- Implement Alpine.js for toggle functionality
- Add smooth transition animations
- Style with Tailwind CSS with rounded corners and shadows
- Include expand/collapse icons
- Optional default open setting for first item
- hide_block toggle option
- Accessible ARIA attributes
- Mobile-responsive design

Generate the complete ACF block PHP template code.
```

---

## 5. Team Members Block Prompt

```
Create a [team_members_block] for my WordPress site [project_name] using [ACF_Pro]. The block needs:

- [repeater_field] for team member cards
- Fields: [profile_image], [name], [position], [bio], [social_links]
- Grid layout: [3_columns_desktop], [2_columns_tablet], [1_column_mobile]
- [hover_effects] on cards
- Optional [filter_by_department] functionality
- Use [Tailwind_CSS] for styling
- Lazy loading for [team_member_images]
- [hide_block] toggle
- Section [title] and [description] fields

Provide the PHP block template following WordPress and ACF best practices.
```

**Example Usage:**

```
Create a team members block for my WordPress site Resplast using ACF Pro. The block needs:

- repeater field for team member cards
- Fields: profile_image, name, position, bio, social_links
- Grid layout: 3 columns desktop, 2 columns tablet, 1 column mobile
- hover effects on cards
- Optional filter by department functionality
- Use Tailwind CSS for styling
- Lazy loading for team member images
- hide_block toggle
- Section title and description fields

Provide the PHP block template following WordPress and ACF best practices.
```

---

## 6. Image Gallery Block Prompt

```
I need a [gallery_block] for WordPress theme [theme_name] using [ACF_gallery_field]. Features required:

- Support for multiple images via [ACF_gallery_field]
- Layout options: [grid], [masonry], or [slider]
- [lightbox_functionality] for full-size viewing
- [lazy_loading] for performance
- Caption support for each image
- [number_of_columns] setting (2, 3, or 4)
- Responsive design with [Tailwind_CSS]
- [hide_block] option
- Optimized images with [WebP/AVIF_support]

Generate the complete PHP template with proper WordPress image handling.
```

**Example Usage:**

```
I need a gallery block for WordPress theme resplast-theme using ACF gallery field. Features required:

- Support for multiple images via ACF gallery field
- Layout options: grid, masonry, or slider
- lightbox functionality for full-size viewing
- lazy loading for performance
- Caption support for each image
- number of columns setting (2, 3, or 4)
- Responsive design with Tailwind CSS
- hide_block option
- Optimized images with WebP/AVIF support

Generate the complete PHP template with proper WordPress image handling.
```

---

## 7. Statistics/Counter Block Prompt

```
Create a [statistics_block] for [project_name] using [ACF_repeater]. Requirements:

- [repeater_field] for stat items
- Each item: [number], [label], [icon], [suffix] (%, +, K, M, etc.)
- [counter_animation] on scroll into view
- Grid layout: [2x2] or [4_columns]
- [background_color] and [text_color] options
- Style with [Tailwind_CSS]
- [hide_block] toggle
- Section [title] field
- Responsive mobile layout

Provide the PHP block template code following WordPress standards.
```

**Example Usage:**

```
Create a home stats block for Resplast using ACF repeater. Requirements:

- repeater field for stat items
- Each item: number, label, icon, suffix (%, +, K, M, etc.)
- counter animation on scroll into view
- Grid layout: 2x2 or 4 columns
- background color and text color options
- Style with Tailwind CSS
- hide_block toggle
- Section title field
- Responsive mobile layout

Provide the PHP block template code following WordPress standards.
```

---

## 8. News/Blog Listing Block Prompt

```
I am building a [news_listing_block] for WordPress site [project_name] using [ACF_and_WP_Query]. Features:

- Display [number_of_posts] from [news_category]
- Show [featured_image], [title], [excerpt], [date], [category_badges]
- Layout options: [grid] or [list_view]
- [load_more] or [pagination] functionality
- [filter_by_category] option
- Style cards with [Tailwind_CSS]
- [hide_block] toggle
- Lazy load images
- [read_more_link] on each post

Generate the complete PHP template with WP_Query implementation.
```

**Example Usage:**

```
I am building a news listing block for WordPress site Resplast using ACF and WP_Query. Features:

- Display 6 posts from news category
- Show featured image, title, excerpt, date, category badges
- Layout options: grid or list view
- load more or pagination functionality
- filter by category option
- Style cards with Tailwind CSS
- hide_block toggle
- Lazy load images
- read more link on each post

Generate the complete PHP template with WP_Query implementation.
```

---

## 9. Contact Form Block Prompt

```
Create a [contact_form_block] for [website_name] using [ACF_and_Contact_Form_7]. Requirements:

- [form_shortcode] field to embed CF7 form
- Optional [form_title] and [description] fields
- [contact_information] section (phone, email, address)
- [map_embed] option (Google Maps)
- Two-column layout: [form_left], [info_right]
- Style with [Tailwind_CSS]
- [hide_block] toggle
- Responsive mobile-stacked layout

Provide the PHP block template integrating Contact Form 7.
```

**Example Usage:**

```
Create a get in touch block for Resplast using ACF and Formidable Forms. Requirements:

- form shortcode field to embed Formidable form
- Optional form title and description fields
- contact information section (phone, email, address)
- map embed option (Google Maps)
- Two-column layout: form left, info right
- Style with Tailwind CSS
- hide_block toggle
- Responsive mobile-stacked layout

Provide the PHP block template integrating Formidable Forms.
```

---

## 10. Testimonials Block Prompt

```
I need a [testimonials_slider_block] for [project_name] using [ACF_repeater]. Features:

- [repeater_field] for testimonial items
- Fields: [quote], [author_name], [author_position], [author_image], [rating]
- [Swiper.js_slider] with autoplay
- [star_rating] display
- [quotation_marks] design element
- Navigation [arrows] and [pagination_dots]
- Style with [Tailwind_CSS]
- [hide_block] toggle
- Responsive design

Generate the complete PHP template with Swiper integration.
```

**Example Usage:**

```
I need a testimonials slider block for Resplast using ACF repeater. Features:

- repeater field for testimonial items
- Fields: quote, author_name, author_position, author_image, rating
- Swiper.js slider with autoplay
- star rating display
- quotation marks design element
- Navigation arrows and pagination dots
- Style with Tailwind CSS
- hide_block toggle
- Responsive design

Generate the complete PHP template with Swiper integration.
```

---

## 11. Services Block Prompt

```
I am creating a [services_block] for [website_name] using [ACF_repeater]. Requirements:

- [repeater_field] for service items
- Each service: [icon], [title], [description], [link]
- Layout: [icon_top] or [icon_left] option
- [hover_animations] with scale effect
- Grid layout: [3_columns_desktop], [2_columns_tablet]
- Background options: [solid_color] or [gradient]
- Style with [Tailwind_CSS]
- [hide_block] toggle
- Section [title] and [subtitle] fields

Generate the PHP block template with proper ACF field handling.
```

**Example Usage:**

```
I am creating a services block for Resplast using ACF repeater. Requirements:

- repeater field for service items
- Each service: icon, title, description, link
- Layout: icon top or icon left option
- hover animations with scale effect
- Grid layout: 3 columns desktop, 2 columns tablet
- Background options: solid color or gradient
- Style with Tailwind CSS
- hide_block toggle
- Section title and subtitle fields

Generate the PHP block template with proper ACF field handling.
```

---

## 12. Pricing Table Block Prompt

```
Create a [pricing_table_block] for [project_name] using [ACF_repeater]. Features needed:

- [repeater_field] for pricing tiers
- Each tier: [plan_name], [price], [currency], [billing_period], [features_list], [cta_button]
- [featured_plan] option with highlighted styling
- [features_list] as sub-repeater or textarea
- Layout: [2_columns], [3_columns], or [4_columns]
- Toggle for [monthly/yearly] pricing
- Style with [Tailwind_CSS] cards and shadows
- [hide_block] toggle
- Responsive mobile-stacked layout

Provide the complete PHP template code.
```

**Example Usage:**

```
Create a pricing table block for Resplast using ACF repeater. Features needed:

- repeater field for pricing tiers
- Each tier: plan_name, price, currency, billing_period, features_list, cta_button
- featured_plan option with highlighted styling
- features_list as sub-repeater or textarea
- Layout: 2 columns, 3 columns, or 4 columns
- Toggle for monthly/yearly pricing
- Style with Tailwind CSS cards and shadows
- hide_block toggle
- Responsive mobile-stacked layout

Provide the complete PHP template code.
```

---

## 13. Video Block Prompt

```
I need a [video_block] for WordPress theme [theme_name] using [ACF_fields]. Requirements:

- Video source options: [YouTube], [Vimeo], or [self-hosted]
- [video_url] or [video_file_upload] fields
- Optional [poster_image] thumbnail
- [autoplay], [loop], and [muted] options
- [play_button_overlay] with custom styling
- Video [title] and [description] fields
- [aspect_ratio] options (16:9, 4:3, 1:1)
- Responsive iframe/video embed
- Style with [Tailwind_CSS]
- [hide_block] toggle

Generate the PHP template with proper video embed handling.
```

**Example Usage:**

```
I need a video block for WordPress theme resplast-theme using ACF fields. Requirements:

- Video source options: YouTube, Vimeo, or self-hosted
- video_url or video_file_upload fields
- Optional poster_image thumbnail
- autoplay, loop, and muted options
- play_button_overlay with custom styling
- Video title and description fields
- aspect_ratio options (16:9, 4:3, 1:1)
- Responsive iframe/video embed
- Style with Tailwind CSS
- hide_block toggle

Generate the PHP template with proper video embed handling.
```

---

## 14. Timeline Block Prompt

```
Create a [timeline_block] for [project_name] using [ACF_repeater]. Features:

- [repeater_field] for timeline events
- Each event: [date/year], [title], [description], [image]
- Layout: [vertical_timeline] or [horizontal_timeline]
- [alternating_sides] for vertical layout
- [milestone_dots] with connecting lines
- [scroll_animations] for events appearing
- Style with [Tailwind_CSS]
- [hide_block] toggle
- Section [title] field
- Responsive mobile layout

Provide the PHP block template code.
```

**Example Usage:**

```
Create a milestones timeline block for Resplast using ACF repeater. Features:

- repeater field for timeline events
- Each event: date/year, title, description, image
- Layout: vertical timeline or horizontal timeline
- alternating sides for vertical layout
- milestone dots with connecting lines
- scroll animations for events appearing
- Style with Tailwind CSS
- hide_block toggle
- Section title field
- Responsive mobile layout

Provide the PHP block template code.
```

---

## 15. Logo Carousel Block Prompt

```
I am building a [logo_carousel_block] for [website_name] using [ACF_repeater]. Requirements:

- [repeater_field] for logo items
- Each logo: [image], [company_name], [link]
- [Swiper.js_carousel] with autoplay
- [infinite_loop] scrolling
- [slides_per_view] option (3, 4, 5, 6)
- Grayscale effect with [color_on_hover]
- Style with [Tailwind_CSS]
- [hide_block] toggle
- Section [title] field (e.g., "Our Partners")
- Responsive breakpoints

Generate the complete PHP template with Swiper integration.
```

**Example Usage:**

```
I am building a partners logo carousel block for Resplast using ACF repeater. Requirements:

- repeater field for logo items
- Each logo: image, company_name, link
- Swiper.js carousel with autoplay
- infinite loop scrolling
- slides_per_view option (3, 4, 5, 6)
- Grayscale effect with color on hover
- Style with Tailwind CSS
- hide_block toggle
- Section title field (e.g., "Our Partners")
- Responsive breakpoints

Generate the complete PHP template with Swiper integration.
```

---

## How to Use These Prompts

### Step 1: Choose a Template

Select the prompt template that matches the type of block you want to create.

### Step 2: Customize Placeholders

Replace all bracketed `[placeholders]` with your specific values:

- `[project_name]` → Your project name
- `[theme_name]` → Your WordPress theme name
- `[programming_language]` → PHP, JavaScript, etc.
- `[framework_library]` → Tailwind CSS, Bootstrap, etc.

### Step 3: Copy to AI Assistant

Paste the customized prompt into your AI assistant:

- ChatGPT
- Claude
- GitHub Copilot
- Other code generation tools

### Step 4: Generate Code

The AI will generate a complete PHP block template following WordPress and ACF best practices.

### Step 5: Save the Template

Save the generated code to:

- **Page-specific blocks:** `templates/blocks/block_name.php`
- **Global/reusable blocks:** `templates/blocks/global/global_block_name.php`

### Step 6: Create ACF Fields

In WordPress admin:

1. Go to **Custom Fields → Field Groups**
2. Create or edit your flexible content field group
3. Add a new layout matching your block name
4. Add the required ACF fields as specified in the prompt

### Step 7: Test & Refine

- Assign the flexible content field to a page template
- Add the block on a page
- Test responsiveness and functionality
- Refine styling as needed

---

## Best Practices

### Naming Conventions

- **Global blocks:** Prefix with `global_`, `cta_`, or `contact_`
- **Page-specific:** Use descriptive names like `hero_block`, `features_block`
- **Files:** Use lowercase with underscores (e.g., `hero_banner_block.php`)

### Required Fields

Always include:

- `hide_block` → True/False toggle for showing/hiding the block
- Proper escaping functions (`esc_html()`, `esc_url()`, `wp_kses_post()`)
- Responsive classes for mobile, tablet, desktop

### Performance

- Use lazy loading for images beyond the viewport
- Implement `resplast_optimized_image()` for image optimization
- Add `priority` loading for hero/above-the-fold images
- Use AVIF/WebP formats with fallbacks

### Accessibility

- Include proper ARIA attributes
- Use semantic HTML tags
- Ensure keyboard navigation support
- Add descriptive alt text for images

---

## Additional Resources

### Project Documentation

- `ACF-BLOCK-GENERATOR.md` - Auto-block generation system documentation
- `PERFORMANCE-SUMMARY.md` - Performance optimization guidelines
- `WARP.md` - Development workflow and architecture overview

### ACF Documentation

- [ACF Flexible Content](https://www.advancedcustomfields.com/resources/flexible-content/)
- [ACF Repeater Field](https://www.advancedcustomfields.com/resources/repeater/)
- [ACF Gallery Field](https://www.advancedcustomfields.com/resources/gallery/)

### WordPress Coding Standards

- [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- [Data Validation & Escaping](https://developer.wordpress.org/plugins/security/data-validation/)

---

## Contributing

If you create new prompt templates that work well, please add them to this document following the same format:

```
## [Number]. [Block Name] Prompt

[Prompt template with bracketed placeholders]

**Example Usage:**
[Filled-in example]
```

---

**Last Updated:** December 2024  
**Version:** 1.0  
**Maintained by:** Resplast Development Team
