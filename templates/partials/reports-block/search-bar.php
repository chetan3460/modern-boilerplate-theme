<?php
/**
 * Reports Block - Search Bar Component
 */
?>

<?php if (isset($show_search) && $show_search): ?>
<!-- Search Bar -->
<div class="mb-6">
    <div class="relative max-w-md">
        <input type="text" 
               id="search-reports" 
               placeholder="Search reports by title or description..."
               class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>
</div>
<?php endif; ?>
