// tailwind.config.js
import { resolve } from 'path';
import typography from '@tailwindcss/typography';

export default {
  content: [
    './*.php',
    './inc/**/*.php',
    './templates/**/*.php',
    './assets/**/*.{js,css}',
    './assets/css/editor-style.css',
    './assets/**/*.html',
    './assets/**/*.js',
    './assets/**/*.php',
  ],
  safelist: [
    'wp-block-post-title',
    'wp-block-post-time-to-read',
    'editor-styles-wrapper',
    'is-layout-flex',
    'is-layout-grid',
    'wp-block',
    {
      pattern: /bg-\[url\(.*\)\]/, // Keep any bg-[url(...)] classes
    },
  ],
  theme: {},
  plugins: [typography],
};
