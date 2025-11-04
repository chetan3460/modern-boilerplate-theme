<?php
/**
 * Single Product Template
 * 
 * Displays individual product pages
 */

get_header(); ?>

<div class="container mx-auto px-4 py-8">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <div class="max-w-6xl mx-auto">
            
            <!-- Product Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php the_title(); ?></h1>
                <?php if (get_the_excerpt()): ?>
                    <p class="text-xl text-gray-600"><?php the_excerpt(); ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Product Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
                
                <!-- Product Image -->
                <?php 
                $product_image = null;
                $default_image = get_stylesheet_directory_uri() . '/assets/images/product-placeholder.svg';
                
                // Try ACF field first
                if (function_exists('get_field')) {
                    $product_image = get_field('product_image');
                }
                
                // If no ACF image, try featured image
                if (!$product_image && has_post_thumbnail()) {
                    $product_image = get_the_post_thumbnail_id();
                }
                
                if ($product_image) {
                    if (is_array($product_image)) {
                        $image_url = $product_image['url'];
                        $image_alt = $product_image['alt'] ?: get_the_title();
                    } else {
                        $image_src = wp_get_attachment_image_src($product_image, 'large');
                        $image_url = $image_src ? $image_src[0] : $default_image;
                        $image_alt = get_post_meta($product_image, '_wp_attachment_image_alt', true) ?: get_the_title();
                    }
                } else {
                    $image_url = $default_image;
                    $image_alt = get_the_title();
                }
                ?>
                
                <div class="aspect-square bg-gray-50 rounded-lg overflow-hidden">
                    <img src="<?php echo esc_url($image_url); ?>" 
                         alt="<?php echo esc_attr($image_alt); ?>" 
                         class="w-full h-full object-cover" />
                </div>
                
                <!-- Product Information -->
                <div class="space-y-8">
                    
                    <?php
                    // Get ALL ACF fields first
                    $product_description = '';
                    $chemistry_type = '';
                    $applications_list = '';
                    $solvent = '';
                    $non_volatile = '';
                    $oil_length = '';
                    $features_benefits = [];
                    $typical_uses = [];
                    $additional_specifications = [];
                    
                    if (function_exists('get_field')):
                        $product_description = get_field('product_description');
                        $chemistry_type = get_field('chemistry_type');
                        $applications_list = get_field('applications_list');
                        $solvent = get_field('solvent');
                        $non_volatile = get_field('non_volatile_percentage');
                        $oil_length = get_field('oil_length_percentage');
                        $features_benefits = get_field('features_benefits') ?: [];
                        $typical_uses = get_field('typical_uses') ?: [];
                        $additional_specifications = get_field('additional_specifications') ?: [];
                    endif;
                    ?>
                    
                    <!-- Product Description -->
                    <?php 
                    $wp_content = get_the_content();
                    
                    // Debug output
                    if (isset($_GET['debug_fields']) && current_user_can('administrator')) {
                        echo '<div style="background: orange; padding: 10px; margin: 10px 0; font-family: monospace; font-size: 12px;">';
                        echo '<strong>Product Description Debug:</strong><br>';
                        echo 'ACF product_description: ' . (empty($product_description) ? 'EMPTY' : 'HAS CONTENT') . '<br>';
                        echo 'WP Content: ' . (empty($wp_content) ? 'EMPTY' : 'HAS CONTENT') . '<br>';
                        if (!empty($product_description)) {
                            echo 'ACF Content preview: ' . substr(strip_tags($product_description), 0, 100) . '...<br>';
                        }
                        echo '</div>';
                    }
                    ?>
                    <?php if ($product_description || $wp_content): ?>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Description</h2>
                            <div class="prose prose-lg text-gray-700">
                                <?php if ($product_description): ?>
                                    <?php echo wp_kses_post($product_description); ?>
                                <?php elseif ($wp_content): ?>
                                    <?php the_content(); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- ACF Fields retrieved above -->
                    
                    <!-- Basic Specifications -->
                    <?php if ($chemistry_type || $applications_list || $solvent || $non_volatile || $oil_length): ?>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Specifications</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                <?php if ($chemistry_type): ?>
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <h3 class="font-medium text-gray-900 mb-1">Chemistry Type</h3>
                                        <p class="text-gray-700"><?php echo esc_html($chemistry_type); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($applications_list): ?>
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <h3 class="font-medium text-gray-900 mb-1">Applications</h3>
                                        <p class="text-gray-700"><?php echo esc_html($applications_list); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($solvent): ?>
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <h3 class="font-medium text-gray-900 mb-1">Solvent</h3>
                                        <p class="text-gray-700"><?php echo esc_html($solvent); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($non_volatile): ?>
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <h3 class="font-medium text-gray-900 mb-1">Non-Volatile %</h3>
                                        <p class="text-gray-700"><?php echo esc_html($non_volatile); ?>%</p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($oil_length): ?>
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <h3 class="font-medium text-gray-900 mb-1">Oil Length %</h3>
                                        <p class="text-gray-700"><?php echo esc_html($oil_length); ?>%</p>
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Features & Benefits -->
                    <?php if (!empty($features_benefits)): ?>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Features & Benefits</h2>
                            
                            <?php 
                            // Debug output
                            if (isset($_GET['debug_fields']) && current_user_can('administrator')) {
                                echo '<div style="background: yellow; padding: 10px; margin: 10px 0; font-family: monospace; font-size: 12px;">';
                                echo '<strong>Features & Benefits Debug:</strong><br>';
                                echo 'Count: ' . count($features_benefits) . '<br>';
                                echo '<pre>' . print_r($features_benefits, true) . '</pre>';
                                echo '</div>';
                            }
                            ?>
                            
                            <ul class="space-y-3">
                                <?php foreach ($features_benefits as $feature): ?>
                                    <?php 
                                    // Use correct sub-field name from ACF structure
                                    $feature_text = $feature['feature_text'] ?? '';
                                    ?>
                                    <?php if (!empty($feature_text)): ?>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-gray-700"><?php echo esc_html($feature_text); ?></span>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Typical Uses -->
                    <?php if (!empty($typical_uses)): ?>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Typical Uses</h2>
                            
                            <?php 
                            // Debug output
                            if (isset($_GET['debug_fields']) && current_user_can('administrator')) {
                                echo '<div style="background: lightblue; padding: 10px; margin: 10px 0; font-family: monospace; font-size: 12px;">';
                                echo '<strong>Typical Uses Debug:</strong><br>';
                                echo 'Count: ' . count($typical_uses) . '<br>';
                                echo '<pre>' . print_r($typical_uses, true) . '</pre>';
                                echo '</div>';
                            }
                            ?>
                            
                            <ul class="space-y-3">
                                <?php foreach ($typical_uses as $use): ?>
                                    <?php 
                                    // Use correct sub-field name from ACF structure
                                    $use_text = $use['use_text'] ?? '';
                                    ?>
                                    <?php if (!empty($use_text)): ?>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-gray-700"><?php echo esc_html($use_text); ?></span>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Additional Specifications -->
                    <?php if (!empty($additional_specifications)): ?>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Additional Specifications</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ($additional_specifications as $spec): ?>
                                    <?php 
                                    $spec_name = $spec['specification_name'] ?? $spec['name'] ?? $spec['spec_name'] ?? '';
                                    $spec_value = $spec['value'] ?? $spec['specification_value'] ?? $spec['spec_value'] ?? '';
                                    ?>
                                    <?php if (!empty($spec_name) && !empty($spec_value)): ?>
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <h3 class="font-medium text-gray-900 mb-1"><?php echo esc_html($spec_name); ?></h3>
                                            <p class="text-gray-700"><?php echo esc_html($spec_value); ?></p>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Technical Data Sheet -->
                    <?php 
                    $technical_datasheet = get_field('technical_datasheet');
                    if ($technical_datasheet): 
                    ?>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Downloads</h2>
                            <a href="<?php echo esc_url($technical_datasheet['url']); ?>" 
                               target="_blank" 
                               rel="noopener"
                               class="inline-flex items-center gap-2 bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Technical Data Sheet
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php endif; // End ACF check ?>
                    
                </div>
                
            </div>
            
            <!-- Back to Products -->
            <div class="text-center">
                <a href="<?php echo home_url('/products/'); ?>" 
                   class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to All Products
                </a>
            </div>
            
        </div>
        
    <?php endwhile; ?>
    
</div>

<?php get_footer(); ?>