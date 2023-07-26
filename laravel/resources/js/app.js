import '../css/main.css'

import { createPinia } from 'pinia'
import { useStyleStore } from '@/Stores/style.js'
import { darkModeKey } from '@/config.js'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { VueReCaptcha } from 'vue-recaptcha-v3'
import VueGtag from 'vue-gtag'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

const pinia = createPinia()

createInertiaApp({
  title: title => `${title} - ${appName}`,
  resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const captchaKey = props.initialPage.props.recaptcha_site_key
    return (
      createApp({ render: () => h(App, props) })
        .use(plugin)
        .use(pinia)
        .use(VueReCaptcha, { siteKey: captchaKey, loaderOptions: { autoHideBadge: true } })
        /* eslint no-undef: 0 */
        .use(ZiggyVue, Ziggy)
        .use(VueGtag, {
          config: { id: 'G-883B9EVYML' },
        })
        .mount(el)
    )
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
