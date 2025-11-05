<?php
// templates/partials/product-card.php
// Usage: include locate_template('templates/partials/product-card.php', false, false);
// Expects variables in scope: $icon (array|null), $item_title (string)
?>
<div class="relative rounded-2xl  overflow-hidden fade-up-stagger aspect-[291:151
]" data-delay="<?php echo number_format($delay, 1); ?>">
  <?php if (!empty($icon['url'])): ?>
    <div class="aspect-[1.927]">
      <img src="<?= esc_url($icon['url']) ?>" alt="<?= esc_attr(
  $icon['alt'] ?: $item_title ?: 'Logo'
) ?>" class="object-cover size-full" loading="lazy" />
    </div>

    <div class="absolute inset-0 rounded-2xl  [background:linear-gradient(148deg,rgba(0,0,0,0.70)_0%,rgba(0,0,0,0.00)_35.59%)]"></div>
  <?php endif; ?>
  <?php if (!empty($item_title)): ?>
    <span class="absolute top-0 left-0 text-white font-semibold text-xs  md:text-base pl-2.5 sm:pl-6 pt-2.5 sm:pt-4 z-10"><?= esc_html(
      $item_title
    ) ?></span>
  <?php endif; ?>
</div>