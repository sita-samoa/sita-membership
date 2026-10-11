import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

const externalDevServerUrl = process.env.GITPOD_WORKSPACE_URL
const externalDevServerHost = externalDevServerUrl
  ? new URL(externalDevServerUrl).hostname
  : null
const externalAppUrl = process.env.APP_URL

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
    allowedHosts: externalDevServerHost ? [externalDevServerHost] : [],
    cors: externalAppUrl ? { origin: externalAppUrl } : undefined,
    hmr: externalDevServerHost
      ? {
          protocol: 'wss',
          clientPort: 443,
          host: externalDevServerHost,
        }
      : {
          host: 'localhost',
        },
  },
})
