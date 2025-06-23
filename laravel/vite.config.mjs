import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

/**
 * Vite configuration for Laravel + Vue.
 * @see https://vitejs.dev/config/
 */
export default defineConfig({
  plugins: [
    laravel({
      input: 'resources/js/app.js',
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  server: {
    host: true,
    hmr: process.env.GITPOD_WORKSPACE_URL
      ? {
          protocol: 'wss',
          clientPort: 443,
          host: process.env.GITPOD_WORKSPACE_URL.replace('https://', '5173-'),
        }
      : {
          host: 'localhost',
        },
  },
})
