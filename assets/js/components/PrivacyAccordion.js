export default class PrivacyAccordion {
  constructor() {
    this.init();
  }

  init() {
    const accordionBlocks = document.querySelectorAll('.privacy_accordion_block');
    
    accordionBlocks.forEach(block => {
      const toggles = block.querySelectorAll('.accordion-toggle');
      const contents = block.querySelectorAll('.accordion-content');
      
      // Close all accordions first
      contents.forEach(el => {
        el.classList.add('hidden');
      });
      toggles.forEach(toggle => {
        toggle.classList.remove('active');
        this.updateIcon(toggle, 'plus');
      });
      
      // Open first accordion by default
      if (toggles.length > 0) {
        toggles[0].classList.add('active');
        const targetId = toggles[0].dataset.accordion;
        const content = document.getElementById(targetId);
        if (content) {
          content.classList.remove('hidden');
          this.updateIcon(toggles[0], 'minus');
        }
      }
      
      // Add click handlers
      toggles.forEach(toggle => {
        toggle.addEventListener('click', () => this.handleToggle(toggle, block));
      });
    });
  }

  updateIcon(toggle, type) {
    const plusIcon = toggle.querySelector('.plus-icon');
    const minusIcon = toggle.querySelector('.minus-icon');
    if (type === 'plus') {
      if (plusIcon) plusIcon.classList.remove('hidden');
      if (minusIcon) minusIcon.classList.add('hidden');
    } else {
      if (plusIcon) plusIcon.classList.add('hidden');
      if (minusIcon) minusIcon.classList.remove('hidden');
    }
  }

  handleToggle(toggle, block) {
    const targetId = toggle.dataset.accordion;
    const content = document.getElementById(targetId);
    const icon = toggle.querySelector('.accordion-icon');
    const isOpen = content.classList.contains('hidden');

    // Close all accordions in this block
    const allContents = block.querySelectorAll('.accordion-content');
    const allToggles = block.querySelectorAll('.accordion-toggle');
    
    allContents.forEach(el => {
      el.classList.add('hidden');
    });
    allToggles.forEach(el => {
      el.classList.remove('active');
      this.updateIcon(el, 'plus');
    });

    // Open clicked accordion
    if (isOpen) {
      content.classList.remove('hidden');
      toggle.classList.add('active');
      this.updateIcon(toggle, 'minus');
    }
  }
}
