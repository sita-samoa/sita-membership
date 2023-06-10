import { defineStore } from "pinia";
import { white as styleDefault } from '@/styles'
import { darkModeKey } from '@/config'

export const useStyleStore = defineStore('style', {
  state: () => ({
    /* Styles */
    asideStyle: '',
    asideScrollbarsStyle: '',
    asideBrandStyle: '',
    asideMenuItemStyle: '',
    asideMenuItemActiveStyle: '',
    asideMenuDropdownStyle: '',
    navBarItemLabelStyle: '',
    navBarItemLabelHoverStyle: '',
    navBarItemLabelActiveColorStyle: '',
    overlayStyle: '',

    /* Dark mode */
    darkMode: false,
  }),
  actions: {
    setStyle() {
      for (const key in styleDefault) {
        this[`${key}Style`] = styleDefault[key]
      }
    },

    setDarkMode(payload = null) {
      this.darkMode = payload !== null ? payload : !this.darkMode

      if (typeof localStorage !== 'undefined') {
        localStorage.setItem(darkModeKey, this.darkMode ? '1' : '0')
      }

      if (typeof document !== 'undefined') {
        document.body.classList[this.darkMode ? 'add' : 'remove']('dark-scrollbars')

        document.documentElement.classList[this.darkMode ? 'add' : 'remove']('dark-scrollbars-compat')
      }
    },
  },
})
