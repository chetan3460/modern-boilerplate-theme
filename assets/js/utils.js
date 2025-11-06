// Media Queries
export const min1024 = window.matchMedia('(min-width: 1024px)');
export const min991 = window.matchMedia('(min-width: 991px)');
export const max1200 = window.matchMedia('(max-width: 1200px)');
export const max767 = window.matchMedia('(max-width: 767px)');
export const max375 = window.matchMedia('(max-width: 375px)');

// utils.js
// HMR reload only for PHP/template changes, not JS/CSS updates
export function forceHMRReload() {
  if (import.meta.hot) {
    // Listen for PHP file changes from live reload plugin
    import.meta.hot.on('custom:php-change', () => {
      console.log('[HMR] PHP file changed, reloading page...');
      window.location.reload();
    });

    // Accept CSS and JS updates without reload
    import.meta.hot.accept(() => {
      console.log('[HMR] Assets updated (CSS/JS)');
    });
  }
}
