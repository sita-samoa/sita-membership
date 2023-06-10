import { mdiAccount, mdiLogout, mdiThemeLightDark } from '@mdi/js'

export default [
  {
    icon: mdiThemeLightDark,
    label: 'Light/Dark',
    isDesktopNoLabel: true,
    isToggleLightDark: true,
  },
  {
    isCurrentUser: true,
    menu: [
      {
        icon: mdiAccount,
        label: 'My Profile',
        route: 'profile.show',
      },
      // {
      //   icon: mdiCogOutline,
      //   label: 'Settings',
      //   route: 'dashboard1',
      // },
      // {
      //   icon: mdiEmail,
      //   label: 'Messages',
      //   route: 'dashboard1',
      // },
      {
        isDivider: true,
      },
      {
        icon: mdiLogout,
        label: 'Log Out',
        isLogout: true,
        route: 'logout',
      },
    ],
  },
]
