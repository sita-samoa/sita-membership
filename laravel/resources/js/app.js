import '../css/main.css'

import { createPinia } from 'pinia'
import { useStyleStore } from '@/Stores/style.js'
import { darkModeKey } from '@/config.js'
import { createApp, h } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { VueReCaptcha } from 'vue-recaptcha-v3'
import * as Sentry from '@sentry/vue'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

const pinia = createPinia()

createInertiaApp({
  title: title => `${title} - ${appName}`,
  resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })

    /**
     * Track Page and Send to Google Analytic
     * */
    const googleAnalyticsGa4 = props.initialPage.props.google_analytics_ga4
    if (import.meta.env.VITE_APP_ENV === 'production' && googleAnalyticsGa4) {
      router.on('navigate', () => {
        gtag('js', new Date())
        gtag('config', googleAnalyticsGa4)
      })
    }

    const captchaKey = props.initialPage.props.recaptcha_site_key

    Sentry.init({
      app,
      dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
      integrations: [Sentry.replayIntegration()],
      replaysSessionSampleRate: 0.1,
      replaysOnErrorSampleRate: 1.0,
    })

    app
      .use(plugin)
      .use(pinia)
      .use(VueReCaptcha, { siteKey: captchaKey, loaderOptions: { autoHideBadge: true } })
      /* eslint no-undef: 0 */
      .use(ZiggyVue, Ziggy)
      .mount(el)

    return app
  },
  progress: {
    color: '#4B5563',
  },
})

const styleStore = useStyleStore(pinia)

/* App style */
styleStore.setStyle()

/* Dark mode */
if ((!localStorage[darkModeKey] && window.matchMedia('(prefers-color-scheme: dark)').matches) || localStorage[darkModeKey] === '1') {
  styleStore.setDarkMode(true)
}
