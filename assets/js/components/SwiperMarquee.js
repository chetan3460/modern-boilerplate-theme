// SwiperMarquee.js
export default class SwiperMarquee {
  constructor(root) {
    this.root = root;
    this.root.querySelectorAll('.marquee-container').forEach(c => this.build(c));
  }

  build(container) {
    const wrapper = container.querySelector('.marquee-wrapper');
    if (!wrapper) return;

    // duplicate all items a few times for an endless loop
    const clones = [...wrapper.children].map(n => n.cloneNode(true));
    for (let i = 0; i < 3; i++) clones.forEach(clone => wrapper.appendChild(clone.cloneNode(true)));

    // measure total width
    const total = [...wrapper.children].reduce((w, el) => w + (el.offsetWidth || 292), 0) / 2;

    // animation settings
    const reverse = container.dataset.reverse === 'true';
    const speed = Math.max(10, (parseInt(container.dataset.marqueeSpeed, 10) || 9000) / 200);
    const name = `mq-${Math.random().toString(36).slice(2)}`;

    // create keyframes and apply class
    document.head.insertAdjacentHTML(
      'beforeend',
      `<style>
        @keyframes ${name} {
          from {transform: translateX(${reverse ? -total : 0}px);}
          to   {transform: translateX(${reverse ? 0 : -total}px);}
        }
        .${name} {animation: ${name} ${speed}s linear infinite;}
      </style>`
    );

    wrapper.classList.add(name);
    wrapper.style.display = 'flex';
    wrapper.style.willChange = 'transform';

    // pause on hover
    container.addEventListener('mouseenter', () => wrapper.style.animationPlayState = 'paused');
    container.addEventListener('mouseleave', () => wrapper.style.animationPlayState = 'running');
  }
}
