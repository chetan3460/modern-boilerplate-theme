// Import necessary modules
import { resolve } from 'path'; // For resolving file paths
import tailwindcss from '@tailwindcss/vite'; // Tailwind CSS plugin for Vite
import fs from 'fs'; // Node.js file system module to read files
import { loadEnv } from 'vite'; // Load environment variables from .env files
import liveReload from 'vite-plugin-live-reload'; // Plugin for live reload on file changes
import { visualizer } from 'rollup-plugin-visualizer'; // Bundle size visualization
import { compression } from 'vite-plugin-compression2'; // Gzip/Brotli compression

// Read version from package.json for cache busting and versioning assets
const pkg = JSON.parse(fs.readFileSync('./package.json', 'utf8'));
const version = pkg.version;

export default ({ mode }) => {
  // Load environment variables based on current mode (development or production)
  const env = loadEnv(mode, process.cwd());

  // Flag to check if current mode is production
  const isProduction = mode === 'production';

  // Get dynamic paths
  const currentDir = process.cwd();
  const themeName = currentDir.split('/').pop(); // Gets 'vite-base-theme'

  // Extract the WordPress installation folder name (e.g., 'wordpress-tailwind-vite')
  const pathParts = currentDir.split('/');
  const htdocsIndex = pathParts.findIndex((part) => part === 'htdocs');
  const wpInstallationName = pathParts[htdocsIndex + 1]; // Gets 'wordpress-tailwind-vite'

  // Build the full relative path from web root
  const relativePath = `/${wpInstallationName}/wp-content/themes/${themeName}`; // Gets /wordpress-tailwind-vite/wp-content/themes/vite-base-theme

  return {
    // The root directory for Vite, where entry files are located
    root: 'assets', // This is where your main.js, css etc. reside

    // Base public path when served in production vs development
    base: isProduction
      ? `${relativePath}/dist/` // Dynamic production base path
      : '/', // Dev server base path

    // Plugins to extend Vite functionality
    plugins: [
      liveReload([
        '../*.php', // Watch PHP files one level up (like index.php)
        '../inc/**/*.php', // Watch PHP files in inc folder recursively
        '../templates/**/*.php', // Watch template PHP files recursively
        'assets/css/**/*.css', // Watch CSS files in assets/css recursively
        'assets/js/**/*.js', // Watch JS files in assets/js recursively
      ]),
      tailwindcss(), // Enable Tailwind CSS processing
      // Production-only plugins
      ...(isProduction
        ? [
            compression({ algorithm: 'gzip', ext: '.gz' }),
            compression({ algorithm: 'brotliCompress', ext: '.br' }),
            visualizer({
              filename: '../dist/.vite/stats.html',
              open: false,
              gzipSize: true,
              brotliSize: true,
            }),
          ]
        : []),
    ],

    // CSS configuration
    css: {
      devSourcemap: true, // Generate source maps for easier debugging of CSS in dev mode
    },

    // Build options for production bundling
    build: {
      outDir: '../dist', // Output directory for production build (outside assets folder)
      emptyOutDir: true, // Clean outDir before each build
      manifest: true, // Generate manifest.json for mapping original filenames to hashed ones (useful in WP)
      assetsInlineLimit: 4096, // Inline small assets as base64 (4kb threshold)
      minify: 'esbuild', // Use esbuild for faster, more compatible minification
      // minify: 'terser', // Use terser for better minification
      manifestFileName: '.vite/manifest.json', // Custom location/name for manifest file
      cssCodeSplit: true, // Enable CSS code splitting for page-specific loading
      target: 'es2020', // Updated modern browsers target (Safari 14+, Chrome 88+)
      modulePreload: {
        polyfill: false, // Disable polyfill for better performance
      },
      sourcemap: false, // Disable source maps in production for smaller builds
      reportCompressedSize: false, // Faster builds by skipping gzip size reporting
      chunkSizeWarningLimit: 700, // Warn if chunks exceed 700kb (matches your performance budget)

      // Rollup-specific options (Vite uses Rollup internally)
      rollupOptions: {
        input: {
          main: resolve(__dirname, 'assets/js/main.js'), // Main JS entry point
          home: resolve(__dirname, 'assets/css/home.css'), // Homepage specific CSS
        },
        output: {
          // Naming pattern for entry JavaScript files, includes version for cache busting
          entryFileNames: `js/[name].[hash].js`,

          // Naming pattern for dynamically imported JS chunks
          chunkFileNames: `js/[name]-chunk.[hash].js`,

          // Naming pattern for asset files (fonts, images, CSS, others)
          assetFileNames: '[ext]/[name].[hash][extname]',

          // Manual chunk splitting for better caching
          manualChunks: (id) => {
            // Vendor libraries in separate chunks
            if (id.includes('node_modules')) {
              if (id.includes('gsap')) return 'vendor-gsap';
              if (id.includes('swiper')) return 'vendor-swiper';
              if (id.includes('jquery')) return 'vendor-jquery';
              // Other node_modules go to general vendor chunk
              return 'vendor';
            }
          },
        },
      },
    },

    // Development server config
    server: {
      port: 3000, // Port number for Vite dev server
      origin: 'http://localhost:3000', // Origin URL, needed for HMR and CORS
      open: `http://localhost${relativePath.replace('/wp-content/themes/' + themeName, '')}/`, // Dynamic URL
      watch: {
        usePolling: true, // Use polling to watch files, helpful in some environments like WSL or Docker
        interval: 100, // Polling interval in ms
        ignored: ['**/node_modules/**', '**/.git/**', '**/dist/**'], // Ignore unnecessary files
      },
      hmr: {
        host: 'localhost', // Hostname for Hot Module Replacement connection
        overlay: true, // Show errors as overlay
      },
      cors: true, // Enable CORS for dev server
    },
  };
};
