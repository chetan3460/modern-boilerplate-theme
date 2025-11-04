<?php
/**
 * Team Members Listing Template
 * Displays team members by category
 */

// Get all team categories
$team_categories = get_terms([
    'taxonomy' => 'team_category',
    'hide_empty' => true,
    'orderby' => 'term_order',
    'order' => 'ASC'
]);

if (empty($team_categories)) {
    echo '<p>No team categories found.</p>';
    return;
}
?>

<div class="team-listing">
    <!-- Team Category Tabs -->
    <div class="team-tabs-container mb-12">
        <div class="flex justify-center gap-4 flex-wrap">
            <?php foreach ($team_categories as $index => $category): ?>
                <button 
                    class="team-tab px-6 py-3 rounded-full border border-primary text-primary hover:bg-primary hover:text-white transition-all <?php echo $index === 0 ? 'active bg-primary text-white' : ''; ?>"
                    data-category="<?php echo esc_attr($category->slug); ?>"
                >
                    <?php echo esc_html($category->name); ?>
                    <span class="ml-2 text-sm">(<?php echo $category->count; ?>)</span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Team Members Grid -->
    <div class="team-members-container">
        <?php foreach ($team_categories as $index => $category): ?>
            <div 
                class="team-category-section <?php echo $index === 0 ? 'active' : 'hidden'; ?>"
                data-category="<?php echo esc_attr($category->slug); ?>"
            >
                <?php
                // Get team members for this category
                $team_members = new WP_Query([
                    'post_type' => 'team_member',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'tax_query' => [
                        [
                            'taxonomy' => 'team_category',
                            'field' => 'slug',
                            'terms' => $category->slug,
                        ],
                    ],
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ]);
                ?>

                <?php if ($team_members->have_posts()): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        <?php while ($team_members->have_posts()): $team_members->the_post(); ?>
                            <?php
                            // Get team member data
                            $position = get_field('position') ?: get_field('job_title') ?: '';
                            $bio = get_field('bio') ?: get_the_excerpt();
                            $linkedin = get_field('linkedin_url') ?: '';
                            $email = get_field('email') ?: '';
                            ?>
                            
                            <div class="team-member-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                                <!-- Member Photo -->
                                <div class="aspect-square bg-gray-100 overflow-hidden">
                                    <?php if (has_post_thumbnail()): ?>
                                        <?php if (function_exists('resplast_optimized_image')): ?>
                                            <?php echo resplast_optimized_image(get_post_thumbnail_id(), 'medium', [
                                                'class' => 'w-full h-full object-cover',
                                                'alt' => get_the_title(),
                                                'lazy' => true
                                            ]); ?>
                                        <?php else: ?>
                                            <?php the_post_thumbnail('medium', [
                                                'class' => 'w-full h-full object-cover',
                                                'alt' => get_the_title(),
                                                'loading' => 'lazy'
                                            ]); ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Member Info -->
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                        <?php the_title(); ?>
                                    </h3>
                                    
                                    <?php if ($position): ?>
                                        <p class="text-primary text-sm font-medium mb-3">
                                            <?php echo esc_html($position); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if ($bio): ?>
                                        <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                            <?php echo wp_trim_words($bio, 20); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <!-- Social Links -->
                                    <?php if ($linkedin || $email): ?>
                                        <div class="flex gap-3">
                                            <?php if ($linkedin): ?>
                                                <a href="<?php echo esc_url($linkedin); ?>" 
                                                   target="_blank" 
                                                   rel="noopener noreferrer"
                                                   class="text-gray-400 hover:text-primary transition-colors">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ($email): ?>
                                                <a href="mailto:<?php echo esc_attr($email); ?>" 
                                                   class="text-gray-400 hover:text-primary transition-colors">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-8">No team members found in this category.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.team-tab');
    const sections = document.querySelectorAll('.team-category-section');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update tabs
            tabs.forEach(t => t.classList.remove('active', 'bg-primary', 'text-white'));
            this.classList.add('active', 'bg-primary', 'text-white');
            
            // Update sections
            sections.forEach(section => {
                if (section.dataset.category === category) {
                    section.classList.remove('hidden');
                    section.classList.add('active');
                } else {
                    section.classList.add('hidden');
                    section.classList.remove('active');
                }
            });
        });
    });
});
</script>