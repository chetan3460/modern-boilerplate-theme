<?php

/**
 * ACF Block Template Generator
 * Automatically generates PHP template files when ACF flexible content layouts are saved
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Block_Generator
{

    public function __construct()
    {
        // Check if ACF is active
        if (!function_exists('acf_get_field_groups')) {
            error_log('ACF Block Generator: ACF plugin not active');
            return;
        }
        
        // Hook into ACF field group save - try multiple hooks
        add_action('acf/save_post', array($this, 'on_acf_save_post'), 20);
        add_action('acf/update_field_group', array($this, 'generate_templates_from_field_group'), 10, 1);
        add_action('acf/field_group/admin_head', array($this, 'check_for_field_groups'), 10);
        
        // Alternative hook for when fields are saved
        add_action('admin_init', array($this, 'init_field_group_monitoring'), 20);
        
        // Debug log
        error_log('ACF Block Generator: Initialized successfully');
    }

    /**
     * Handle ACF save post event
     */
    public function on_acf_save_post($post_id) {
        // Only process if this is a field group being saved
        if (get_post_type($post_id) === 'acf-field-group') {
            error_log('ACF Block Generator: Field group saved, processing layouts...');
            $this->process_field_group_on_save($post_id);
        }
    }
    
    /**
     * Process field group when saved
     */
    public function process_field_group_on_save($field_group_id) {
        $field_group = acf_get_field_group($field_group_id);
        if ($field_group) {
            $fields = acf_get_fields($field_group['key']);
            if ($fields) {
                $this->process_fields_for_layouts($fields);
            }
        }
    }

    /**
     * Check for field groups on admin init
     */
    public function init_field_group_monitoring() {
        if (function_exists('acf_get_field_groups')) {
            $field_groups = acf_get_field_groups();
            error_log('ACF Block Generator: Found ' . count($field_groups) . ' field groups');
            
            foreach ($field_groups as $field_group) {
                $this->check_field_group_for_new_layouts($field_group);
            }
        }
    }
    
    /**
     * Check specific field group for flexible content
     */
    public function check_field_group_for_new_layouts($field_group) {
        if (!$field_group || !isset($field_group['key'])) {
            return;
        }
        
        // Get full field group data
        $full_field_group = acf_get_field_group($field_group['key']);
        if ($full_field_group) {
            $fields = acf_get_fields($full_field_group['key']);
            if ($fields) {
                $parent_name = isset($field_group['title']) ? sanitize_file_name(strtolower(str_replace(' ', '_', $field_group['title']))) : null;
                $this->process_fields_for_layouts($fields, $parent_name);
            }
        }
    }
    
    /**
     * Process fields to find flexible content layouts
     */
    public function process_fields_for_layouts($fields, $parent_field_group_name = null) {
        foreach ($fields as $field) {
            if ($field['type'] === 'flexible_content' && isset($field['layouts'])) {
                error_log('ACF Block Generator: Found flexible content field: ' . $field['name']);
                foreach ($field['layouts'] as $layout) {
                    $this->create_block_template($layout, $parent_field_group_name);
                }
            }
        }
    }
    
    /**
     * Check for field groups - alternative hook
     */
    public function check_for_field_groups() {
        error_log('ACF Block Generator: admin_head hook fired');
    }

    /**
     * Generate block templates when field group is updated
     */
    public function generate_templates_from_field_group($field_group)
    {
        error_log('ACF Block Generator: Field group update hook fired');
        
        if (!$field_group || !isset($field_group['fields'])) {
            error_log('ACF Block Generator: No field group data or fields');
            return;
        }

        // Extract parent name from field group title
        $parent_name = isset($field_group['title']) ? sanitize_file_name(strtolower(str_replace(' ', '_', $field_group['title']))) : null;

        // Find flexible content fields
        foreach ($field_group['fields'] as $field) {
            if ($field['type'] === 'flexible_content' && isset($field['layouts'])) {
                foreach ($field['layouts'] as $layout) {
                    $this->create_block_template($layout, $parent_name);
                }
            }
        }
    }

    /**
     * Create block template file
     */
    public function create_block_template($layout, $parent_field_group_name = null)
    {
        error_log('ACF Block Generator: create_block_template called for layout: ' . print_r($layout['name'] ?? 'unknown', true));
        
        if (!isset($layout['name']) || empty($layout['name']) || !isset($layout['sub_fields'])) {
            error_log('ACF Block Generator: Layout missing name or sub_fields');
            return;
        }
        
        // Skip if block name is invalid
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $layout['name'])) {
            error_log('ACF Block Generator: Invalid block name: ' . $layout['name']);
            return;
        }

        $block_name = $layout['name'];
        $block_label = $layout['label'] ?? ucwords(str_replace('_', ' ', $block_name));

        // Determine where to create the file
        $is_global = $this->should_be_global($block_name);
        $blocks_dir = get_template_directory() . '/templates/blocks';
        
        // Create base directories if they don't exist
        if (!file_exists($blocks_dir)) {
            wp_mkdir_p($blocks_dir);
        }

        // Determine final directory path
        if ($is_global) {
            $target_dir = $blocks_dir . '/global';
        } elseif ($parent_field_group_name && $parent_field_group_name !== 'about_panels') {
            // Create nested folder for parent field group (e.g., about_panels/milestones_block.php)
            $target_dir = $blocks_dir . '/' . $parent_field_group_name;
        } else {
            // Default folder for non-grouped blocks
            $target_dir = $blocks_dir;
        }

        // Create target directory if it doesn't exist
        if (!file_exists($target_dir)) {
            wp_mkdir_p($target_dir);
        }

        $file_path = $target_dir . '/' . $block_name . '.php';

        // Don't overwrite existing files
        if (file_exists($file_path)) {
            return;
        }

        // Generate template content
        $template_content = $this->generate_template_content($layout);

        // Write file with error handling
        $result = @file_put_contents($file_path, $template_content);
        
        if ($result === false) {
            error_log("ACF Block Generator: Failed to create template for '{$block_name}' at {$file_path} - Permission denied");
            error_log("ACF Block Generator: Run fix-permissions.sh script to fix this issue");
            return;
        }
        
        // Set proper permissions on the created file
        @chmod($file_path, 0664);
        
        // Generate corresponding CSS file
        $this->create_block_stylesheet($block_name, $target_dir);
        
        // Generate corresponding JS component file
        $this->create_block_component($block_name);
        
        // Log success
        if ($parent_field_group_name) {
            error_log("ACF Block Generator: Created template for '{$block_name}' under '{$parent_field_group_name}' at {$file_path}");
        } else {
            error_log("ACF Block Generator: Created template for '{$block_name}' at {$file_path}");
        }
    }

    /**
     * Create corresponding CSS file for the block
     */
    private function create_block_stylesheet($block_name, $target_dir)
    {
        // Determine CSS directory
        $css_dir = get_template_directory() . '/assets/css/custom/blocks';
        
        // Create CSS directory if it doesn't exist
        if (!file_exists($css_dir)) {
            wp_mkdir_p($css_dir);
        }
        
        $css_file_path = $css_dir . '/' . $block_name . '.css';
        
        // Don't overwrite existing CSS files
        if (file_exists($css_file_path)) {
            return;
        }
        
        // Generate CSS template content
        $css_content = $this->generate_css_content($block_name);
        
        // Write CSS file
        $result = @file_put_contents($css_file_path, $css_content);
        
        if ($result === false) {
            error_log("ACF Block Generator: Failed to create CSS for '{$block_name}' at {$css_file_path}");
            return;
        }
        
        // Set proper permissions
        @chmod($css_file_path, 0664);
        
        // Add import to style.css (disabled due to permission issues on macOS)
        // $this->add_css_import_to_style($block_name);
        
        error_log("ACF Block Generator: Created CSS file for '{$block_name}' at {$css_file_path}");
        error_log("ACF Block Generator: Add this line to style.css: @import './custom/blocks/{$block_name}.css';");
    }

    /**
     * Generate CSS template content
     */
    private function generate_css_content($block_name)
    {
        $css_content = ".{$block_name} {\n";
        $css_content .= "  /* Add your styles here */\n";
        $css_content .= "}\n";
        
        return $css_content;
    }

    /**
     * Create corresponding JS component file for the block
     */
    private function create_block_component($block_name)
    {
        $js_dir = get_template_directory() . '/assets/js/components';
        
        if (!file_exists($js_dir)) {
            wp_mkdir_p($js_dir);
        }
        
        // Convert block_name to PascalCase for component name
        $component_name = $this->to_pascal_case($block_name);
        $js_file_path = $js_dir . '/' . $component_name . '.js';
        
        // Don't overwrite existing JS files
        if (file_exists($js_file_path)) {
            return;
        }
        
        // Generate JS component content
        $js_content = $this->generate_component_content($component_name, $block_name);
        
        // Write JS file
        $result = @file_put_contents($js_file_path, $js_content);
        
        if ($result === false) {
            error_log("ACF Block Generator: Failed to create JS component for '{$component_name}' at {$js_file_path}");
            return;
        }
        
        // Set proper permissions
        @chmod($js_file_path, 0664);
        
        // Add to componentList.js
        $this->add_to_component_list($component_name);
        
        error_log("ACF Block Generator: Created JS component for '{$component_name}' at {$js_file_path}");
    }

    /**
     * Generate JS component template content
     */
    private function generate_component_content($component_name, $block_name)
    {
        $js_content = "export default class {$component_name} {\n";
        $js_content .= "  constructor() {\n";
        $js_content .= "    this.blockEl = document.querySelector('[data-component=\"{$component_name}\"]');\n";
        $js_content .= "    \n";
        $js_content .= "    if (!this.blockEl) return;\n";
        $js_content .= "    \n";
        $js_content .= "    this.init();\n";
        $js_content .= "  }\n";
        $js_content .= "  \n";
        $js_content .= "  init() {\n";
        $js_content .= "    // Initialize your component here\n";
        $js_content .= "    console.log('{$component_name} initialized');\n";
        $js_content .= "  }\n";
        $js_content .= "}\n";
        
        return $js_content;
    }

    /**
     * Convert snake_case to PascalCase
     */
    private function to_pascal_case($string)
    {
        return str_replace('_', '', ucwords($string, '_'));
    }

    /**
     * Add component entry to componentList.js
     */
    private function add_to_component_list($component_name)
    {
        $component_list_path = get_template_directory() . '/assets/js/componentList.js';
        
        if (!file_exists($component_list_path)) {
            error_log("ACF Block Generator: componentList.js not found at {$component_list_path}");
            return;
        }
        
        $content = file_get_contents($component_list_path);
        $entry = "  {$component_name}: {\n    mobile: true,\n  },";
        
        // Check if component already exists
        if (strpos($content, $component_name . ':') !== false) {
            return;
        }
        
        // Find the closing brace and add before it
        $closing_brace_pos = strrpos($content, '};');
        if ($closing_brace_pos !== false) {
            $new_content = substr_replace($content, $entry . "\n};", $closing_brace_pos, 2);
            
            $result = @file_put_contents($component_list_path, $new_content);
            if ($result === false) {
                error_log("ACF Block Generator: Failed to add '{$component_name}' to componentList.js - permission denied");
                error_log("ACF Block Generator: Manually add: {$component_name}: {{ mobile: true, }}");
            } else {
                error_log("ACF Block Generator: Added '{$component_name}' to componentList.js");
            }
        } else {
            error_log("ACF Block Generator: Could not find closing brace in componentList.js");
        }
    }

    /**
     * Add CSS import to style.css if not already present
     */
    private function add_css_import_to_style($block_name)
    {
        $style_css_path = get_template_directory() . '/assets/css/style.css';
        
        if (!file_exists($style_css_path)) {
            error_log("ACF Block Generator: style.css not found at {$style_css_path}");
            return;
        }
        
        $style_content = file_get_contents($style_css_path);
        $import_line = "@import './custom/blocks/{$block_name}.css';";
        
        // Check if import already exists
        if (strpos($style_content, $import_line) !== false) {
            return;
        }
        
        // Find the AUTO-GENERATED BLOCKS section
        $marker = '/* AUTO-GENERATED BLOCKS - Add new imports below */';
        $end_marker = '/* END AUTO-GENERATED BLOCKS */';
        
        if (strpos($style_content, $marker) !== false && strpos($style_content, $end_marker) !== false) {
            // Insert before END marker
            $new_import = $import_line . "\n";
            $style_content = str_replace(
                $end_marker,
                $new_import . $end_marker,
                $style_content
            );
            
            file_put_contents($style_css_path, $style_content);
            error_log("ACF Block Generator: Added CSS import for '{$block_name}' to style.css");
        } else {
            error_log("ACF Block Generator: AUTO-GENERATED BLOCKS markers not found in style.css. Please add them manually.");
        }
    }

    /**
     * Determine if block should be global based on naming patterns
     */
    private function should_be_global($block_name)
    {
        $global_patterns = [
            'global_',
            'cta_',
            'contact_',
            'newsletter_',
            'testimonial',
            'get_in_touch',
            'certificate_block',
            'footer_',
            'header_',
            'banner_',
            'call_to_action',
            'social_',
            'form_',
            'subscribe',
            'download_',
            'popup_',
            'modal_'
        ];

        foreach ($global_patterns as $pattern) {
            if (strpos($block_name, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate PHP template content
     */
    private function generate_template_content($layout)
    {
        $block_name = $layout['name'];
        $block_label = $layout['label'] ?? ucwords(str_replace('_', ' ', $block_name));
        $sub_fields = $layout['sub_fields'] ?? [];

        $php_content = "<?php\n\n";
        $php_content .= "/**\n";
        $php_content .= " * {$block_label} Template\n";
        $php_content .= " * Auto-generated by ACF Block Generator\n";
        $php_content .= " *\n";
        $php_content .= " * ACF Fields:\n";

        // Document fields
        foreach ($sub_fields as $field) {
            $php_content .= " * - {$field['name']} ({$field['type']})\n";

            // Document repeater/group sub-fields
            if (isset($field['sub_fields'])) {
                foreach ($field['sub_fields'] as $sub_field) {
                    $php_content .= " *   - {$sub_field['name']} ({$sub_field['type']})\n";
                }
            }
        }

        $php_content .= " */\n\n";

        // Generate field variables
        foreach ($sub_fields as $field) {
            if ($field['name'] === 'hide_block') continue; // Skip hide_block, handled separately

            $field_name = $field['name'];
            $php_content .= "\${$field_name} = get_sub_field('{$field_name}') ?: '';\n";
        }

        $php_content .= "\n";
        $php_content .= "// Include hide block functionality\n";
        $php_content .= "include locate_template('templates/blocks/hide_block.php', false, false);\n\n";

        // Generate condition check
        $main_fields = array_filter($sub_fields, function ($field) {
            return !in_array($field['name'], ['hide_block']);
        });

        if (!empty($main_fields)) {
            $field_checks = [];
            foreach ($main_fields as $field) {
                $field_checks[] = "\${$field['name']}";
            }
            $condition = implode(' || ', array_slice($field_checks, 0, 3)); // Check first 3 fields
        $php_content .= "if (!\$hide_block && ({$condition})): ?>\n";
        } else {
            $php_content .= "if (!\$hide_block): ?>\n";
        }

        // Convert block name to PascalCase for component name
        $component_name = $this->to_pascal_case($block_name);
        
        $php_content .= "  <section class=\"{$block_name} py-12\" data-component=\"{$component_name}\">\n";
        $php_content .= "    <div class=\"container-fluid\">\n\n";

        // Get block name for component name conversion
        $block_name_local = $layout['name'];
        $component_name_local = $this->to_pascal_case($block_name_local);
        
        // Generate section heading if title/description fields exist
        $title_field = null;
        $description_field = null;
        
        foreach ($sub_fields as $field) {
            if (strpos($field['name'], 'title') !== false || strpos($field['name'], 'heading') !== false) {
                $title_field = $field['name'];
            }
            if (strpos($field['name'], 'description') !== false || strpos($field['name'], 'content') !== false) {
                $description_field = $field['name'];
            }
        }
        
        if ($title_field || $description_field) {
            $php_content .= "      <?php if (";
            $conditions = [];
            if ($title_field) $conditions[] = "\${$title_field}";
            if ($description_field) $conditions[] = "\${$description_field}";
            $php_content .= implode(' || ', $conditions) . "): ?>\n";
            $php_content .= "        <div class=\"section-heading text-center\">\n";
            if ($title_field) {
                $php_content .= "          <?php if (\${$title_field}): ?>\n";
                $php_content .= "            <h2 class=\"fade-text\"><?= esc_html(\${$title_field}); ?></h2>\n";
                $php_content .= "          <?php endif; ?>\n";
            }
            if ($description_field) {
                $php_content .= "          <?php if (\${$description_field}): ?>\n";
                $php_content .= "            <div class=\"anim-uni-in-up\"><?= wp_kses_post(\${$description_field}); ?></div>\n";
                $php_content .= "          <?php endif; ?>\n";
            }
            $php_content .= "        </div>\n";
            $php_content .= "      <?php endif; ?>\n\n";
        }

        // Generate field output for remaining fields
        foreach ($sub_fields as $field) {
            if ($field['name'] === 'hide_block') continue;
            if (($title_field && $field['name'] === $title_field) || ($description_field && $field['name'] === $description_field)) continue;

            $php_content .= $this->generate_field_output($field, '      ');
        }

        $php_content .= "\n    </div>\n";
        $php_content .= "  </section>\n";
        $php_content .= "<?php endif; ?>\n";

        return $php_content;
    }

    /**
     * Generate output code for different field types
     */
    private function generate_field_output($field, $indent = '')
    {
        $output = '';
        $field_name = $field['name'];
        $field_type = $field['type'];

        switch ($field_type) {
            case 'text':
            case 'textarea':
                if (strpos($field_name, 'title') !== false || strpos($field_name, 'heading') !== false) {
                    $output .= "{$indent}<?php if (\${$field_name}): ?>\n";
                    $output .= "{$indent}  <h2 class=\"fade-text\"><?= esc_html(\${$field_name}); ?></h2>\n";
                    $output .= "{$indent}<?php endif; ?>\n\n";
                } else {
                    $output .= "{$indent}<?php if (\${$field_name}): ?>\n";
                    $output .= "{$indent}  <div class=\"{$field_name}-text\"><?= esc_html(\${$field_name}); ?></div>\n";
                    $output .= "{$indent}<?php endif; ?>\n\n";
                }
                break;

            case 'wysiwyg':
                $output .= "{$indent}<?php if (\${$field_name}): ?>\n";
                $output .= "{$indent}  <div class=\"anim-uni-in-up\">\n";
                $output .= "{$indent}    <?= wp_kses_post(\${$field_name}); ?>\n";
                $output .= "{$indent}  </div>\n";
                $output .= "{$indent}<?php endif; ?>\n\n";
                break;

            case 'image':
                $output .= "{$indent}<?php if (\${$field_name}): ?>\n";
                $output .= "{$indent}  <div class=\"{$field_name}-wrapper\">\n";
                $output .= "{$indent}    <?php if (function_exists('resplast_optimized_image')): ?>\n";
                $output .= "{$indent}      <?php echo resplast_optimized_image(\${$field_name}['ID'], 'large', [\n";
                $output .= "{$indent}        'class' => '{$field_name}-image',\n";
                $output .= "{$indent}        'alt' => \${$field_name}['alt'] ?: '',\n";
                $output .= "{$indent}        'lazy' => true\n";
                $output .= "{$indent}      ]); ?>\n";
                $output .= "{$indent}    <?php else: ?>\n";
                $output .= "{$indent}      <img src=\"<?php echo esc_url(\${$field_name}['url']); ?>\" \n";
                $output .= "{$indent}           alt=\"<?php echo esc_attr(\${$field_name}['alt']); ?>\" \n";
                $output .= "{$indent}           class=\"{$field_name}-image\">\n";
                $output .= "{$indent}    <?php endif; ?>\n";
                $output .= "{$indent}  </div>\n";
                $output .= "{$indent}<?php endif; ?>\n\n";
                break;

            case 'link':
                $output .= "{$indent}<?php if (\${$field_name} && \${$field_name}['url']): ?>\n";
                $output .= "{$indent}  <a href=\"<?php echo esc_url(\${$field_name}['url']); ?>\"\n";
                $output .= "{$indent}     class=\"btn btn-primary\"\n";
                $output .= "{$indent}     <?php if (\${$field_name}['target']): ?>target=\"<?php echo esc_attr(\${$field_name}['target']); ?>\"<?php endif; ?>>\n";
                $output .= "{$indent}    <?php echo esc_html(\${$field_name}['title'] ?: 'Read More'); ?>\n";
                $output .= "{$indent}  </a>\n";
                $output .= "{$indent}<?php endif; ?>\n\n";
                break;

            case 'repeater':
                $output .= "{$indent}<?php if (\${$field_name}): ?>\n";
                $output .= "{$indent}  <div class=\"{$field_name}-grid\">\n";
                $output .= "{$indent}    <?php foreach (\${$field_name} as \$item): ?>\n";
                $output .= "{$indent}      <div class=\"{$field_name}-item\">\n";

                // Generate sub-field outputs
                if (isset($field['sub_fields'])) {
                    foreach ($field['sub_fields'] as $sub_field) {
                        $sub_field_name = $sub_field['name'];
                        $output .= "{$indent}        <?php if (\$item['{$sub_field_name}']): ?>\n";
                        if ($sub_field['type'] === 'image') {
                            $output .= "{$indent}          <img src=\"<?php echo esc_url(\$item['{$sub_field_name}']['url']); ?>\" alt=\"<?php echo esc_attr(\$item['{$sub_field_name}']['alt']); ?>\">\n";
                        } else {
                            $output .= "{$indent}          <div class=\"{$sub_field_name}\"><?= esc_html(\$item['{$sub_field_name}']); ?></div>\n";
                        }
                        $output .= "{$indent}        <?php endif; ?>\n";
                    }
                }

                $output .= "{$indent}      </div>\n";
                $output .= "{$indent}    <?php endforeach; ?>\n";
                $output .= "{$indent}  </div>\n";
                $output .= "{$indent}<?php endif; ?>\n\n";
                break;

            default:
                $output .= "{$indent}<?php if (\${$field_name}): ?>\n";
                $output .= "{$indent}  <div class=\"{$field_name}-field\"><?= esc_html(\${$field_name}); ?></div>\n";
                $output .= "{$indent}<?php endif; ?>\n\n";
        }

        return $output;
    }
}

// Initialize the generator
new ACF_Block_Generator();
