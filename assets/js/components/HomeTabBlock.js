// export default class HomeTabBlock {
//   constructor(element) {
//     this.root = element || document;
//     this.mq = window.matchMedia('(max-width: 1023px)');
//     this.init();
//   }

//   init = () => {
//     this.setDomMap();
//     this.applyMode();
//     if (this.mq.addEventListener) this.mq.addEventListener('change', this.applyMode);
//     else if (this.mq.addListener) this.mq.addListener(this.applyMode);
//   };

//   setDomMap = () => {
//     this.containers = $(this.root)
//       .find('.tabs-container, .tabs-container')
//       .filter(function () {
//         const already = $(this).hasClass('js-initialized');
//         if (!already) $(this).addClass('js-initialized');
//         return !already;
//       });
//   };

//   switchToOpen = ($item) => {
//     $item.find('.closed-state').hide();
//     $item.find('.open-state').show();
//   };

//   switchToClosed = ($item) => {
//     $item.find('.closed-state').show();
//     $item.find('.open-state').hide();
//   };

//   enableMobile = (container) => {
//     const $c = $(container);
//     const items = $c.find('.tab-item');
//     items.off('.desktop');

//     // Reset all items
//     items.removeClass('active').attr('aria-expanded', 'false');
//     items.each((_, item) => {
//       const $item = $(item);
//       item.style.removeProperty('height');
//       item.style.removeProperty('max-height');
//       item.style.removeProperty('min-height');
//       this.switchToClosed($item);
//     });

//     // Activate first item
//     if (items.length) {
//       const $first = $(items.get(0));
//       $first.addClass('active').attr('aria-expanded', 'true');
//       const firstEl = $first.get(0);
//       firstEl.style.setProperty('height', '247.974px', 'important');
//       firstEl.style.setProperty('max-height', 'none', 'important');
//       firstEl.style.setProperty('min-height', '247.974px', 'important');
//       this.switchToOpen($first);
//     }

//     items.off('.mobile').on('click.mobile', (e) => {
//       e.preventDefault();
//       e.stopImmediatePropagation();
//       const $clicked = $(e.currentTarget);
//       const isActive = $clicked.hasClass('active');

//       if (isActive) {
//         // Close current item
//         $clicked.removeClass('active').attr('aria-expanded', 'false');
//         const el = $clicked.get(0);
//         el.style.removeProperty('height');
//         el.style.removeProperty('max-height');
//         el.style.removeProperty('min-height');
//         this.switchToClosed($clicked);
//         return;
//       }

//       // Close all siblings
//       items.removeClass('active').attr('aria-expanded', 'false');
//       items.each((_, item) => {
//         item.style.removeProperty('height');
//         item.style.removeProperty('max-height');
//         item.style.removeProperty('min-height');
//         this.switchToClosed($(item));
//       });

//       // Open clicked item
//       $clicked.addClass('active').attr('aria-expanded', 'true');
//       const el = $clicked.get(0);
//       el.style.setProperty('height', '247.974px', 'important');
//       el.style.setProperty('max-height', 'none', 'important');
//       el.style.setProperty('min-height', '247.974px', 'important');
//       this.switchToOpen($clicked);

//       // Smooth scroll
//       el?.scrollIntoView?.({ behavior: 'smooth', block: 'start', inline: 'nearest' });
//     });
//   };

//   enableDesktop = (container) => {
//     const $c = $(container);
//     const items = $c.find('.tab-item');

//     // Clear mobile styles
//     items.each(function () {
//       this.style.removeProperty('height');
//       this.style.removeProperty('max-height');
//       this.style.removeProperty('min-height');
//     });
//     items.off('.mobile');

//     // Reset all to closed state
//     items.removeClass('active').attr('aria-expanded', 'false');
//     items.each((_, item) => {
//       this.switchToClosed($(item));
//     });

//     // Activate first item
//     if (items.length) {
//       const $first = $(items.get(0));
//       $first.addClass('active').attr('aria-expanded', 'true');
//       this.switchToOpen($first);
//     }

//     // Add hover functionality
//     items.off('.desktop').on('mouseenter.desktop', (e) => {
//       const $hovered = $(e.currentTarget);
//       if ($hovered.hasClass('active')) return;

//       // Close all siblings
//       $hovered.siblings().removeClass('active').attr('aria-expanded', 'false');
//       $hovered.siblings().each((_, sibling) => {
//         this.switchToClosed($(sibling));
//       });

//       // Open hovered item
//       $hovered.addClass('active').attr('aria-expanded', 'true');
//       this.switchToOpen($hovered);
//     });

//     // Keep click functionality as backup/mobile fallback
//     items.on('click.desktop', (e) => {
//       const $clicked = $(e.currentTarget);
//       if ($clicked.hasClass('active')) return;

//       // Close all siblings
//       $clicked.siblings().removeClass('active').attr('aria-expanded', 'false');
//       $clicked.siblings().each((_, sibling) => {
//         this.switchToClosed($(sibling));
//       });

//       // Open clicked item
//       $clicked.addClass('active').attr('aria-expanded', 'true');
//       this.switchToOpen($clicked);
//     });
//   };

//   applyMode = () => {
//     this.containers.each((_, container) => {
//       if (this.mq.matches) this.enableMobile(container);
//       else this.enableDesktop(container);
//     });
//   };
// }
export default class HomeTabBlock {
  constructor(root) {
    this.root = root || document;
    this.mq = window.matchMedia('(max-width: 1023px)');
    this.init();
  }

  init() {
    this.setDomMap();
    this.applyMode();
    if (this.mq.addEventListener) this.mq.addEventListener('change', this.applyMode);
    else if (this.mq.addListener) this.mq.addListener(this.applyMode);
  }

  setDomMap() {
    // pick up any .tabs-container that hasn’t been initialized yet
    this.containers = Array.from(this.root.querySelectorAll('.tabs-container')).filter((el) => {
      if (el.classList.contains('js-initialized')) return false;
      el.classList.add('js-initialized');
      return true;
    });
  }

  showOpen(item) {
    const closed = item.querySelector('.closed-state');
    const open = item.querySelector('.open-state');
    if (closed) closed.style.display = 'none';
    if (open) open.style.display = 'block';
  }

  showClosed(item) {
    const closed = item.querySelector('.closed-state');
    const open = item.querySelector('.open-state');
    if (closed) closed.style.display = 'block';
    if (open) open.style.display = 'none';
  }

  enableMobile(container) {
    const items = container.querySelectorAll('.tab-item');

    // reset
    items.forEach((item) => {
      item.classList.remove('active');
      item.setAttribute('aria-expanded', 'false');
      this.showClosed(item);
      item.style.removeProperty('height');
      // remove old event listeners
      item.replaceWith(item.cloneNode(true));
    });

    const freshItems = container.querySelectorAll('.tab-item');

    freshItems.forEach((item, i) => {
      // first item open by default
      if (i === 0) {
        item.classList.add('active');
        item.setAttribute('aria-expanded', 'true');
        item.style.height = '248px';
        this.showOpen(item);
      }

      item.addEventListener('click', (e) => {
        e.preventDefault();
        const isActive = item.classList.contains('active');

        // close all
        freshItems.forEach((it) => {
          it.classList.remove('active');
          it.setAttribute('aria-expanded', 'false');
          it.style.removeProperty('height');
          this.showClosed(it);
        });

        if (!isActive) {
          item.classList.add('active');
          item.setAttribute('aria-expanded', 'true');
          item.style.height = '248px';
          this.showOpen(item);

          // scroll after height is applied
          requestAnimationFrame(() => {
            const rect = item.getBoundingClientRect();
            if (rect.top < 0 || rect.bottom > window.innerHeight) {
              item.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
          });
        }
      });
    });
  }

  enableDesktop(container) {
    const items = container.querySelectorAll('.tab-item');

    // reset styles and remove previous event listeners
    items.forEach((item) => {
      item.classList.remove('active');
      item.setAttribute('aria-expanded', 'false');
      this.showClosed(item);
      item.style.removeProperty('height');
      item.replaceWith(item.cloneNode(true));
    });

    const freshItems = container.querySelectorAll('.tab-item');

    freshItems.forEach((item, i) => {
      if (i === 0) {
        item.classList.add('active');
        item.setAttribute('aria-expanded', 'true');
        this.showOpen(item);
      }

      // hover interaction removed — click only

      // click fallback
      item.addEventListener('click', () => {
        if (item.classList.contains('active')) return;

        freshItems.forEach((it) => {
          it.classList.remove('active');
          it.setAttribute('aria-expanded', 'false');
          this.showClosed(it);
        });

        item.classList.add('active');
        item.setAttribute('aria-expanded', 'true');
        this.showOpen(item);
      });
    });
  }

  applyMode = () => {
    this.containers.forEach((container) => {
      if (this.mq.matches) this.enableMobile(container);
      else this.enableDesktop(container);
    });
  };
}
