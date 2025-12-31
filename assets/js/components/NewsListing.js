export default class NewsListing {
  constructor(section) {
    this.section = section;
    if (!this.section) return;

    // Read config
    this.ajaxUrl = this.section.dataset.ajaxUrl;
    this.nonce = this.section.dataset.nonce;
    this.taxonomy = this.section.dataset.taxonomy;
    this.initialPP = Number(this.section.dataset.initialPpp || '6');
    this.loadPP = Number(this.section.dataset.loadPpp || '3');

    // Elements
    this.selCat = this.section.querySelector('#' + this.section.id + '-cat');
    this.selSort = this.section.querySelector('#' + this.section.id + '-sort');
    this.grid = this.section.querySelector('#' + this.section.id + '-grid');
    this.loadBtn = this.section.querySelector('#' + this.section.id + '-load');
    this.loadBtnContainer = this.section.querySelector('#' + this.section.id + '-load-container');
    this.loader = this.section.querySelector('#' + this.section.id + '-loader');
    this.form = this.section.querySelector('#' + this.section.id + '-search-form');
    this.input = this.section.querySelector('#' + this.section.id + '-search');
    this.endMsg = this.section.querySelector('#' + this.section.id + '-end');

    this.isLoading = false;
    this.loadedCount = Number(this.section.dataset.initialCount || '0');

    this.setupDropdown('cat', this.selCat);
    this.setupDropdown('sort', this.selSort);
    this.bindEvents();
  }

  setupDropdown(type, hiddenSelect) {
    const wrap = this.section.querySelector('[data-dd="' + type + '"]');
    if (!wrap || !hiddenSelect) return;
    const btn = wrap.querySelector('button');
    const menu = wrap.querySelector('.dd-menu');
    const label = wrap.querySelector('.dd-label');

    const close = () => {
      menu.classList.add('hidden');
      btn.setAttribute('aria-expanded', 'false');
    };
    const open = () => {
      menu.classList.remove('hidden');
      btn.setAttribute('aria-expanded', 'true');
    };

    btn.addEventListener('click', () => (menu.classList.contains('hidden') ? open() : close()));
    wrap.querySelectorAll('.dd-item').forEach((item) => {
      item.addEventListener('click', () => {
        hiddenSelect.value = item.dataset.value;
        label.textContent = item.textContent.trim();
        close();
        this.resetAndFetch(type);
      });
    });
    document.addEventListener('click', (e) => {
      if (!wrap.contains(e.target)) close();
    });
  }

  bindEvents() {
    if (this.selCat) this.selCat.addEventListener('change', () => this.resetAndFetch('filter'));
    if (this.selSort) this.selSort.addEventListener('change', () => this.resetAndFetch('sort'));
    if (this.form)
      this.form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.resetAndFetch('search');
      });
    if (this.input) {
      this.input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          this.resetAndFetch('search');
        }
      });
      let t;
      this.input.addEventListener('input', () => {
        clearTimeout(t);
        t = setTimeout(() => this.resetAndFetch('search'), 500);
      });
    }
    if (this.loadBtn) {
      this.loadBtn.addEventListener('click', () =>
        this.request({
          perPage: this.loadPP,
          offset: this.loadedCount,
          replace: false,
          origin: 'loadmore',
        })
      );
    }
  }

  setLoading(state, origin) {
    this.isLoading = state;
    const isLoadMore = origin === 'loadmore';

    if (this.loader) this.loader.classList.toggle('hidden', !state || isLoadMore);
    if (this.grid) this.grid.classList.toggle('hidden', state && !isLoadMore);

    if (this.loadBtn) {
      this.loadBtn.disabled = state;
      const svg = this.loadBtn.querySelector('svg');
      const label = this.loadBtn.querySelector('.btn-text');
      if (svg) svg.classList.toggle('hidden', !(state && isLoadMore));
      if (label) label.textContent = state && isLoadMore ? 'Loading…' : 'View More';
      this.loadBtn.classList.toggle('opacity-50', state && !isLoadMore);
      this.loadBtn.classList.toggle('cursor-not-allowed', state && !isLoadMore);
    }
  }

  request({ perPage, offset, replace, origin = 'filter' }) {
    if (this.isLoading) return;
    this.setLoading(true, origin);

    console.log('[NewsListing] Request:', {
      perPage,
      offset,
      replace,
      origin,
      loadedCount: this.loadedCount,
    });

    const params = new URLSearchParams();
    params.append('action', 'resplast_news_query');
    params.append('nonce', this.nonce);
    params.append('posts_per_page', perPage);
    params.append('offset', offset);
    params.append('taxonomy', this.taxonomy);
    params.append('category', this.selCat ? this.selCat.value || 'all' : 'all');
    params.append('sort', this.selSort ? this.selSort.value || 'newest' : 'newest');
    params.append('search', this.input && this.input.value ? this.input.value.trim() : '');

    fetch(this.ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: params.toString(),
    })
      .then((r) => r.json())
      .then((resp) => {
        if (!resp || !resp.success) return;
        const data = resp.data || {};
        const html = (data.html || '').trim();
        const returned = Number(data.returned || 0);

        console.log('[NewsListing] Response:', {
          returned,
          has_more: data.has_more,
          found_posts: data.found_posts,
          replace,
        });

        if (replace) {
          this.grid.innerHTML =
            html ||
            '<div class="col-span-full text-center text-gray-500 py-10">No news found. Try adjusting your filters or search.</div>';
          this.loadedCount = returned;
        } else {
          if (html) {
            this.grid.insertAdjacentHTML('beforeend', html);
            this.loadedCount += returned;
          }
        }

        console.log('[NewsListing] Updated loadedCount:', this.loadedCount);

        if (!data.has_more || !html) {
          this.loadBtn.disabled = true;
          this.loadBtn.classList.add('hidden');
          if (this.loadBtnContainer) this.loadBtnContainer.classList.add('hidden');
          if (!replace && this.endMsg) this.endMsg.classList.remove('hidden');
        } else {
          this.loadBtn.disabled = false;
          this.loadBtn.classList.remove('hidden');
          if (this.loadBtnContainer) this.loadBtnContainer.classList.remove('hidden');
          if (this.endMsg) this.endMsg.classList.add('hidden');
        }
      })
      .catch(() => {})
      .finally(() => this.setLoading(false, origin));
  }

  resetAndFetch(origin = 'filter') {
    if (this.endMsg) this.endMsg.classList.add('hidden');
    if (this.loadBtn) {
      this.loadBtn.disabled = false;
      this.loadBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
    this.request({ perPage: this.initialPP, offset: 0, replace: true, origin });
  }
}
